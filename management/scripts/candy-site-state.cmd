@echo off
setlocal
set "PYTHONDONTWRITEBYTECODE=1"
set "PYTHONUTF8=1"
set "PYTHONIOENCODING=utf-8"

set "BUNDLED_GIT=%USERPROFILE%\.cache\codex-runtimes\codex-primary-runtime\dependencies\native\git\cmd"
if exist "%BUNDLED_GIT%\git.exe" set "PATH=%BUNDLED_GIT%;%PATH%"

set "PYTHON=%LOCALAPPDATA%\Programs\Python\Python312\python.exe"
if exist "%PYTHON%" goto run
set "PYTHON=%USERPROFILE%\.cache\codex-runtimes\codex-primary-runtime\dependencies\python\python.exe"
if exist "%PYTHON%" goto run
where python >nul 2>nul
if errorlevel 1 goto no_python
set "PYTHON=python"
goto run

:no_python
echo Python executable was not found. 1>&2
exit /b 9009

:run
"%PYTHON%" "%~dp0candy_site_state.py" %*
exit /b %ERRORLEVEL%
