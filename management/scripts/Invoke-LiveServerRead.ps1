[CmdletBinding()]
param(
    [Parameter(Mandatory = $true)]
    [ValidateSet(
        "SelfTest",
        "Server",
        "Php",
        "Process",
        "Network",
        "Disk",
        "Cron",
        "Files",
        "WebConfig",
        "Mail"
    )]
    [string]$Check
)

Set-StrictMode -Version 2.0
$ErrorActionPreference = "Stop"

$Ssh = "$env:WINDIR\System32\OpenSSH\ssh.exe"
$SshKeygen = "$env:WINDIR\System32\OpenSSH\ssh-keygen.exe"

$HostName = "firststar.kir.jp"
$UserName = "firststar"

$PrivateKey = "$env:USERPROFILE\.ssh\candy_readonly_rsa"
$KnownHosts = "$env:USERPROFILE\.ssh\candy_readonly_known_hosts"

$ExpectedHostFingerprint = "SHA256:pd3aC2b5kEW5ME9EIz5wgA25qdZRMxypMFN9E86EApc"

foreach ($Path in @($Ssh,$SshKeygen,$PrivateKey,$KnownHosts)) {
    if (-not (Test-Path -LiteralPath $Path -PathType Leaf)) {
        throw "Required file not found: $Path"
    }
}

$Fingerprint = (& $SshKeygen -lf $KnownHosts -E sha256 2>&1 | Out-String)

if ($LASTEXITCODE -ne 0) {
    throw "Failed to read known_hosts."
}

if ($Fingerprint -notmatch [regex]::Escape($ExpectedHostFingerprint)) {
    throw "Server Host Key fingerprint mismatch."
}

$Args = @(
    "-F","none",
    "-T",
    "-o","HostKeyAlgorithms=+ssh-rsa",
    "-o","PubkeyAcceptedAlgorithms=+ssh-rsa",
    "-o","BatchMode=yes",
    "-o","PasswordAuthentication=no",
    "-o","KbdInteractiveAuthentication=no",
    "-o","GSSAPIAuthentication=no",
    "-o","PreferredAuthentications=publickey",
    "-o","IdentitiesOnly=yes",
    "-o","StrictHostKeyChecking=yes",
    "-o","UpdateHostKeys=no",
    "-o","ClearAllForwardings=yes",
    "-o","RequestTTY=no",
    "-o","LogLevel=ERROR",
    "-o","ConnectTimeout=10",
    "-o","ConnectionAttempts=1",
    "-o",("UserKnownHostsFile=" + $KnownHosts),
    "-i",$PrivateKey,
    ($UserName + "@" + $HostName),
    $Check
)

$OldPreference = $ErrorActionPreference

try {
    $ErrorActionPreference = "Continue"
    $Output = & $Ssh @Args 2>&1
    $ExitCode = $LASTEXITCODE
}
finally {
    $ErrorActionPreference = $OldPreference
}

if ($Output) {
    $Output | ForEach-Object { Write-Output $_ }
}

if ($ExitCode -ne 0) {
    exit $ExitCode
}

if ($Check -eq "SelfTest") {
    $Text = $Output | Out-String

    if ($Text -notmatch "CANDY_SERVER_READONLY_OK") {
        throw "Unexpected SelfTest response."
    }

    if ($Text -notmatch "ROOT_EXISTS=YES") {
        throw "Candy production root was not confirmed."
    }
}

exit 0
