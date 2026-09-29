param(
    [switch]$Preview
)
$ErrorActionPreference = 'Stop'
$env:PYTHONDONTWRITEBYTECODE = '1'

$command = if ($Preview) { 'preview' } else { 'write' }
& (Join-Path $PSScriptRoot 'candy-site-state.cmd') $command
exit $LASTEXITCODE
