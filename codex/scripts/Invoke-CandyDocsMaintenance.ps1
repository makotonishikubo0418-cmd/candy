param()
$ErrorActionPreference = 'Stop'

$script = Join-Path $PSScriptRoot 'generate_candy_management_docs.py'
$fallbackPython = 'C:\Users\teamp\.cache\codex-runtimes\codex-primary-runtime\dependencies\python\python.exe'

$candidates = @()
$python = Get-Command python -ErrorAction SilentlyContinue
if ($python) {
    $candidates += [pscustomobject]@{ Name = 'python'; File = $python.Source; Args = @() }
}
$py = Get-Command py -ErrorAction SilentlyContinue
if ($py) {
    $candidates += [pscustomobject]@{ Name = 'py'; File = $py.Source; Args = @('-3') }
}
if (Test-Path -LiteralPath $fallbackPython) {
    $candidates += [pscustomobject]@{ Name = 'codex-runtime-python'; File = $fallbackPython; Args = @() }
}

foreach ($candidate in $candidates) {
    try {
        & $candidate.File @($candidate.Args) --version *> $null
        if ($LASTEXITCODE -ne 0) { continue }
        & $candidate.File @($candidate.Args) $script
        exit $LASTEXITCODE
    } catch {
        continue
    }
}

throw 'Python was not found. Install Python on PATH as python or py, or run inside Codex with the bundled runtime available.'
