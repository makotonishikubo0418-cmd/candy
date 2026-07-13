param(
    [string]$RepositoryRoot = (Resolve-Path (Join-Path $PSScriptRoot '..\..\..')).Path,
    [string]$OutputPath = (Join-Path $PSScriptRoot '..\docs\CANDY_PRODUCTION_MIGRATION_INVENTORY.csv')
)

$ErrorActionPreference = 'Stop'

$oldDirectoryName = 'HP_' + [char]0x65E7 + [char]0x30C7 + [char]0x30FC + [char]0x30BF
$oldRoot = Join-Path $RepositoryRoot $oldDirectoryName
$newRoot = Join-Path $RepositoryRoot 'HP'

if (-not (Test-Path -LiteralPath $oldRoot -PathType Container)) {
    throw "Old snapshot directory not found: $oldRoot"
}
if (-not (Test-Path -LiteralPath $newRoot -PathType Container)) {
    throw "Current HP directory not found: $newRoot"
}

$oldRoot = (Resolve-Path -LiteralPath $oldRoot).Path
$newRoot = (Resolve-Path -LiteralPath $newRoot).Path

function Get-RelativeFileMap {
    param([string]$Root)

    $map = @{}
    Get-ChildItem -LiteralPath $Root -File -Recurse -Force | ForEach-Object {
        $relative = $_.FullName.Substring($Root.Length + 1).Replace('\', '/')
        $map[$relative] = $_
    }
    return $map
}

function Test-AutoDeployExcluded {
    param([string]$Path)

    if ($Path -eq 'AGENTS.md' -or $Path.ToLowerInvariant().EndsWith('.md')) {
        return $true
    }
    $prefixes = @(
        'codex/', 'log/', 'Text_area_data/', 'Text_blog_data/',
        'Text_hotel_data/', '.well-known/', '.git/', '.github/', '.vscode/'
    )
    foreach ($prefix in $prefixes) {
        if ($Path.StartsWith($prefix, [StringComparison]::Ordinal)) {
            return $true
        }
    }
    return $false
}

function Get-Category {
    param([string]$Path, [string]$State)

    $top = ($Path -split '/')[0]
    if (Test-AutoDeployExcluded $Path) { return 'EXCLUDED_MANAGEMENT' }
    if ($State -eq 'OLD_ONLY' -and $top -in @('bannerjisaku','daysnavi','ekichika','kaitei','loveL','magaimg','media','vanilla')) { return 'LEGACY_ONLY_ASSET' }
    if ($Path -notmatch '/') { return 'ROOT_ENTRY_OR_CONFIG' }
    switch ($top) {
        'includefile' { return 'SHARED_ENGINE' }
        'source' { return 'SOURCE_TEMPLATE' }
        'css' { return 'STYLE' }
        'js' { return 'SCRIPT' }
        'imgHtml' { return 'IMAGE' }
        'imgCss' { return 'IMAGE' }
        'font' { return 'FONT' }
        'movie' { return 'MEDIA' }
        default { return 'OTHER' }
    }
}

function Get-MigrationPhase {
    param([string]$Path, [string]$State, [string]$Category)

    if ($Category -eq 'EXCLUDED_MANAGEMENT') { return 'EXCLUDED' }
    if ($State -eq 'OLD_ONLY') { return 'HOLD_OLD_ONLY' }
    if ($Path -in @('.htaccess','index.php','index.html','main.php','sitemap.xml')) { return 'PHASE_5_ENTRY_SWITCH' }
    switch ($Category) {
        'IMAGE' { return 'PHASE_1_STATIC_ASSET' }
        'FONT' { return 'PHASE_1_STATIC_ASSET' }
        'MEDIA' { return 'PHASE_1_STATIC_ASSET' }
        'STYLE' { return 'PHASE_1_STATIC_ASSET' }
        'SCRIPT' { return 'PHASE_1_STATIC_ASSET' }
        'SHARED_ENGINE' { return 'PHASE_2_SHARED_ENGINE' }
        'SOURCE_TEMPLATE' { return 'PHASE_3_SOURCE' }
        'ROOT_ENTRY_OR_CONFIG' { return 'PHASE_4_PUBLIC_ENTRY' }
        default { return 'PHASE_4_OTHER' }
    }
}

function Get-Risk {
    param([string]$Path, [string]$State, [string]$Category)

    if ($Path -eq 'includefile/dataset_base.php') { return 'CRITICAL_RUNTIME_PATH' }
    if ($Path -in @('.htaccess','index.php','index.html','main.php')) { return 'CRITICAL_ENTRY' }
    if ($State -eq 'OLD_ONLY' -and $Path -notmatch '/') { return 'HIGH_OLD_ROOT_REMAINS' }
    if ($State -eq 'CHANGED' -and $Category -in @('SHARED_ENGINE','SOURCE_TEMPLATE','STYLE','SCRIPT')) { return 'HIGH_SHARED_CHANGE' }
    if ($State -eq 'OLD_ONLY') { return 'REVIEW_BEFORE_DELETE' }
    if ($State -eq 'NEW_ONLY') { return 'VERIFY_DEPENDENCIES' }
    if ($State -eq 'CHANGED') { return 'VERIFY_BEFORE_REPLACE' }
    return 'LOW_IDENTICAL'
}

$oldMap = Get-RelativeFileMap $oldRoot
$newMap = Get-RelativeFileMap $newRoot
$inventoryRelativePath = 'codex/docs/CANDY_PRODUCTION_MIGRATION_INVENTORY.csv'
$newMap.Remove($inventoryRelativePath)
$allPaths = @($oldMap.Keys + $newMap.Keys | Sort-Object -Unique)

$rows = foreach ($path in $allPaths) {
    $oldFile = $oldMap[$path]
    $newFile = $newMap[$path]
    $oldHash = $null
    $newHash = $null

    if ($oldFile) { $oldHash = (Get-FileHash -LiteralPath $oldFile.FullName -Algorithm SHA256).Hash }
    if ($newFile) { $newHash = (Get-FileHash -LiteralPath $newFile.FullName -Algorithm SHA256).Hash }

    if ($oldFile -and $newFile) {
        $state = if ($oldHash -eq $newHash) { 'IDENTICAL' } else { 'CHANGED' }
    } elseif ($oldFile) {
        $state = 'OLD_ONLY'
    } else {
        $state = 'NEW_ONLY'
    }

    $category = Get-Category $path $state
    $phase = Get-MigrationPhase $path $state $category
    $risk = Get-Risk $path $state $category
    $autoDeployEligible = (-not (Test-AutoDeployExcluded $path)) -and $null -ne $newFile
    $decision = switch ($state) {
        'IDENTICAL' { 'NO_CHANGE_REQUIRED' }
        'OLD_ONLY' { 'PRESERVE_PENDING_REVIEW' }
        'NEW_ONLY' { if ($autoDeployEligible) { 'DEPLOY_BY_PHASE' } else { 'DO_NOT_DEPLOY' } }
        'CHANGED' { if ($autoDeployEligible) { 'REPLACE_BY_PHASE_AFTER_VERIFY' } else { 'DO_NOT_DEPLOY' } }
    }

    [pscustomobject]@{
        Path = $path
        State = $state
        Category = $category
        MigrationPhase = $phase
        Risk = $risk
        OldSize = if ($oldFile) { $oldFile.Length } else { $null }
        NewSize = if ($newFile) { $newFile.Length } else { $null }
        OldSHA256 = $oldHash
        NewSHA256 = $newHash
        OldModified = if ($oldFile) { $oldFile.LastWriteTime.ToString('yyyy-MM-dd HH:mm:ss') } else { $null }
        NewModified = if ($newFile) { $newFile.LastWriteTime.ToString('yyyy-MM-dd HH:mm:ss') } else { $null }
        AutoDeployEligible = $autoDeployEligible
        Decision = $decision
        ServerVerified = $false
        ReviewStatus = 'NOT_REVIEWED'
        Notes = ''
    }
}

$outputDirectory = Split-Path -Parent $OutputPath
New-Item -ItemType Directory -Path $outputDirectory -Force | Out-Null
$rows | Export-Csv -LiteralPath $OutputPath -NoTypeInformation -Encoding UTF8

Write-Output "Inventory: $OutputPath"
$rows | Group-Object State | Sort-Object Name | ForEach-Object {
    Write-Output ("{0}={1}" -f $_.Name, $_.Count)
}
