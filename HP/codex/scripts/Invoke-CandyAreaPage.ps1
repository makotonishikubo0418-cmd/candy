param(
    [Parameter(Mandatory = $true)]
    [ValidateSet("Build", "Check", "AuditInputs")]
    [string]$Mode,
    [string]$InputText,
    [switch]$DryRun,
    [switch]$Force,
    [switch]$RequirePhp,
    [switch]$IncludeCompletion
)

$ErrorActionPreference = "Stop"
$scriptPath = Join-Path $PSScriptRoot "candy_area_page.py"
$localPython = Join-Path $env:LOCALAPPDATA "Programs\Python\Python312\python.exe"
if (Test-Path -LiteralPath $localPython) {
    $python = $localPython
} else {
    $pythonCommand = Get-Command python -ErrorAction SilentlyContinue
    if (-not $pythonCommand) { throw "Python 3 が見つかりません。" }
    $python = $pythonCommand.Source
}

$arguments = @($scriptPath)
switch ($Mode) {
    "Build" {
        if (-not $InputText) { throw "Build には -InputText が必要です。" }
        $arguments += @("build", "--input", $InputText)
        if ($DryRun) { $arguments += "--dry-run" }
        if ($Force) { $arguments += "--force" }
    }
    "Check" {
        if (-not $InputText) { throw "Check には -InputText が必要です。" }
        $arguments += @("check", "--input", $InputText)
        if ($RequirePhp) { $arguments += "--require-php" }
    }
    "AuditInputs" {
        $arguments += "audit-inputs"
        if ($IncludeCompletion) { $arguments += "--include-completion" }
    }
}

& $python @arguments
exit $LASTEXITCODE
