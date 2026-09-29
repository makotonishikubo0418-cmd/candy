@echo off
setlocal
set "PYTHONDONTWRITEBYTECODE=1"

set "BUNDLED_GIT=%USERPROFILE%\.cache\codex-runtimes\codex-primary-runtime\dependencies\native\git\cmd"
if exist "%BUNDLED_GIT%\git.exe" set "PATH=%BUNDLED_GIT%;%PATH%"

set "PYTHON=%LOCALAPPDATA%\Programs\Python\Python312\python.exe"
if exist "%PYTHON%" goto run
set "PYTHON=%USERPROFILE%\.cache\codex-runtimes\codex-primary-runtime\dependencies\python\python.exe"
if exist "%PYTHON%" goto run
set "PYTHON=python"
:run
if /I "%~1"=="publish-next" goto publish
if /I "%~1"=="publish" goto publish
if /I "%~1"=="publish-self-test" goto publish
if /I "%~1"=="resume" goto publish
if /I "%~1"=="target-next" goto targetgate
if /I "%~1"=="target-check" goto targetgate
if /I "%~1"=="replace-images" goto imagereplace
"%PYTHON%" "%~dp0candy_area_page.py" %*
exit /b %ERRORLEVEL%

:targetgate
set "PYTHONUTF8=1"
set "PYTHONIOENCODING=utf-8"
"%PYTHON%" "%~dp0candy_area_target_gate.py" %*
exit /b %ERRORLEVEL%

:imagereplace
set "PYTHONUTF8=1"
set "PYTHONIOENCODING=utf-8"
"%PYTHON%" "%~dp0candy_area_image_replace.py" %*
exit /b %ERRORLEVEL%

:publish
set "PYTHONUTF8=1"
set "PYTHONIOENCODING=utf-8"
"%PYTHON%" "%~dp0candy_area_publish.py" %*
exit /b %ERRORLEVEL%
