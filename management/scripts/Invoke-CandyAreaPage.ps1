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
$env:PYTHONDONTWRITEBYTECODE = "1"
$arguments = @()
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

& (Join-Path $PSScriptRoot "candy-area.cmd") @arguments
exit $LASTEXITCODE
