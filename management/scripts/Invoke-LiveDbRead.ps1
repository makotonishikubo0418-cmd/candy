[CmdletBinding(DefaultParameterSetName = 'Status')]
param(
    [Parameter(Mandatory = $true, ParameterSetName = 'SelfTest')]
    [switch]$SelfTest,

    [Parameter(Mandatory = $true, ParameterSetName = 'Status')]
    [switch]$Status,

    [Parameter(Mandatory = $true, ParameterSetName = 'Tables')]
    [switch]$Tables,

    [Parameter(Mandatory = $true, ParameterSetName = 'Schema')]
    [ValidatePattern('^[A-Za-z0-9_]+$')]
    [string]$Schema,

    [Parameter(Mandatory = $true, ParameterSetName = 'Create')]
    [ValidatePattern('^[A-Za-z0-9_]+$')]
    [string]$Create,

    [Parameter(Mandatory = $true, ParameterSetName = 'Indexes')]
    [ValidatePattern('^[A-Za-z0-9_]+$')]
    [string]$Indexes,

    [Parameter(Mandatory = $true, ParameterSetName = 'Count')]
    [ValidatePattern('^[A-Za-z0-9_]+$')]
    [string]$Count,

    [Parameter(Mandatory = $true, ParameterSetName = 'Rows')]
    [ValidatePattern('^[A-Za-z0-9_]+$')]
    [string]$Rows,

    [Parameter(ParameterSetName = 'Rows')]
    [ValidateRange(1, 200)]
    [int]$Limit = 50,

    [Parameter(ParameterSetName = 'Rows')]
    [ValidateRange(0, 1000000)]
    [int]$Offset = 0
)

Set-StrictMode -Version 2.0
$ErrorActionPreference = 'Stop'

$Ssh = Join-Path $env:WINDIR 'System32\OpenSSH\ssh.exe'
$SshKeygen = Join-Path $env:WINDIR 'System32\OpenSSH\ssh-keygen.exe'

$HostName = 'firststar.kir.jp'
$UserName = 'firststar'

$PrivateKey = Join-Path $env:USERPROFILE '.ssh\candy_db_readonly_rsa'
$KnownHosts = Join-Path $env:USERPROFILE '.ssh\candy_readonly_known_hosts'

$ExpectedHostFingerprint = 'SHA256:pd3aC2b5kEW5ME9EIz5wgA25qdZRMxypMFN9E86EApc'
$ExpectedDatabase = 'fsg_db'
$ExpectedDbUser = 'firststar@153.127.232.193'

function Fail {
    param(
        [string]$Message,
        [int]$Code = 1
    )

    Write-Error $Message
    exit $Code
}

function Require-File {
    param(
        [string]$Path,
        [string]$Label
    )

    if (-not (Test-Path -LiteralPath $Path -PathType Leaf)) {
        Fail ($Label + ' not found: ' + $Path)
    }
}

function Test-HostFingerprint {
    $out = & $SshKeygen -lf $KnownHosts -E sha256 2>&1

    if ($LASTEXITCODE -ne 0) {
        Fail 'Failed to read Candy known_hosts fingerprint.'
    }

    $text = ($out | Out-String)

    if ($text -notmatch [regex]::Escape($ExpectedHostFingerprint)) {
        Fail 'Candy server Host Key fingerprint mismatch.'
    }
}

function Invoke-DbCommand {
    param([string]$Command)

    $args = @(
        '-F','none',
        '-T',
        '-o','HostKeyAlgorithms=+ssh-rsa',
        '-o','PubkeyAcceptedAlgorithms=+ssh-rsa',
        '-o','BatchMode=yes',
        '-o','PasswordAuthentication=no',
        '-o','KbdInteractiveAuthentication=no',
        '-o','GSSAPIAuthentication=no',
        '-o','PreferredAuthentications=publickey',
        '-o','IdentitiesOnly=yes',
        '-o','StrictHostKeyChecking=yes',
        '-o','UpdateHostKeys=no',
        '-o','ClearAllForwardings=yes',
        '-o','RequestTTY=no',
        '-o','LogLevel=ERROR',
        '-o','ConnectTimeout=10',
        '-o','ConnectionAttempts=1',
        '-o',('UserKnownHostsFile=' + $KnownHosts),
        '-i',$PrivateKey,
        ($UserName + '@' + $HostName),
        $Command
    )

    $previousErrorActionPreference = $ErrorActionPreference
    $previousConsoleOutputEncoding = [Console]::OutputEncoding

    try {
        # OpenSSH returns UTF-8. Windows PowerShell 5.1 otherwise decodes
        # redirected native stdout/stderr using the current console encoding,
        # which can corrupt Japanese text on CP932 systems.
        [Console]::OutputEncoding = [System.Text.UTF8Encoding]::new($false)
        $ErrorActionPreference = 'Continue'
        $output = & $Ssh @args 2>&1
        $exitCode = $LASTEXITCODE
    }
    finally {
        [Console]::OutputEncoding = $previousConsoleOutputEncoding
        $ErrorActionPreference = $previousErrorActionPreference
    }

    [pscustomobject]@{
        ExitCode = $exitCode
        Text = ($output | Out-String)
    }
}

function Test-StatusOutput {
    param([string]$Text)

    $lines = @(
        $Text -split "`r?`n" |
        Where-Object { $_ -and $_.Trim().Length -gt 0 }
    )

    if ($lines.Count -lt 2) {
        return $false
    }

    $header = @($lines[0] -split "`t")
    $row = @($lines[1] -split "`t")

    if ($header.Count -lt 6 -or $row.Count -lt 6) {
        return $false
    }

    if ($header[0] -ne 'database_name') {
        return $false
    }

    if ($header[1] -ne 'db_user') {
        return $false
    }

    if ($row[0] -ne $ExpectedDatabase) {
        return $false
    }

    if ($row[1] -ne $ExpectedDbUser) {
        return $false
    }

    if ([string]::IsNullOrWhiteSpace($row[3])) {
        return $false
    }

    return $true
}

function Test-SelfTestOutput {
    param([string]$Text)

    $lines = @(
        $Text -split "`r?`n" |
        Where-Object { $_ -and $_.Trim().Length -gt 0 }
    )

    if ($lines.Count -lt 2) {
        return $false
    }

    $header = @($lines[0] -split "`t")
    $row = @($lines[1] -split "`t")

    if ($header.Count -lt 4 -or $row.Count -lt 4) {
        return $false
    }

    if ($header[0] -ne 'readonly_status') {
        return $false
    }

    if ($header[1] -ne 'database_name') {
        return $false
    }

    if ($header[2] -ne 'db_user') {
        return $false
    }

    if ($row[0] -ne 'CANDY_DB_READONLY_OK') {
        return $false
    }

    if ($row[1] -ne $ExpectedDatabase) {
        return $false
    }

    if ($row[2] -ne $ExpectedDbUser) {
        return $false
    }

    return $true
}

Require-File -Path $Ssh -Label 'ssh.exe'
Require-File -Path $SshKeygen -Label 'ssh-keygen.exe'
Require-File -Path $PrivateKey -Label 'Candy DB READ-ONLY private key'
Require-File -Path $KnownHosts -Label 'Candy known_hosts'

Test-HostFingerprint

$selfTestResult = Invoke-DbCommand -Command 'selftest'

if ($selfTestResult.ExitCode -ne 0) {
    Fail (
        'Candy DB READ-ONLY SelfTest failed. ExitCode=' +
        $selfTestResult.ExitCode +
        ' Output=' +
        $selfTestResult.Text.Trim()
    ) $selfTestResult.ExitCode
}

if (-not (Test-SelfTestOutput -Text $selfTestResult.Text)) {
    Fail 'Candy DB READ-ONLY SelfTest returned unexpected identity.'
}

$statusResult = Invoke-DbCommand -Command 'status'

if ($statusResult.ExitCode -ne 0) {
    Fail (
        'Candy DB READ-ONLY status check failed. ExitCode=' +
        $statusResult.ExitCode +
        ' Output=' +
        $statusResult.Text.Trim()
    ) $statusResult.ExitCode
}

if (-not (Test-StatusOutput -Text $statusResult.Text)) {
    Fail 'Candy DB READ-ONLY status check returned unexpected identity.'
}

if ($PSCmdlet.ParameterSetName -eq 'SelfTest') {
    Write-Output 'CANDY_DB_READONLY_LAUNCHER_OK'
    Write-Output $selfTestResult.Text.TrimEnd()
    Write-Output $statusResult.Text.TrimEnd()
    exit 0
}

$command = $null

switch ($PSCmdlet.ParameterSetName) {
    'Status' {
        Write-Output $statusResult.Text.TrimEnd()
        exit 0
    }

    'Tables' {
        $command = 'tables'
    }

    'Schema' {
        $command = 'schema ' + $Schema
    }

    'Create' {
        $command = 'create ' + $Create
    }

    'Indexes' {
        $command = 'indexes ' + $Indexes
    }

    'Count' {
        $command = 'count ' + $Count
    }

    'Rows' {
        $command = 'rows ' + $Rows + ' ' + $Limit + ' ' + $Offset
    }

    default {
        Fail ('Unsupported parameter set: ' + $PSCmdlet.ParameterSetName)
    }
}

$result = Invoke-DbCommand -Command $command

if ($result.ExitCode -ne 0) {
    Fail (
        'Candy DB READ-ONLY command failed. ExitCode=' +
        $result.ExitCode +
        ' Output=' +
        $result.Text.Trim()
    ) $result.ExitCode
}

Write-Output $result.Text.TrimEnd()
exit 0
