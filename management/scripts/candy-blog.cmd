@echo off
setlocal
set "PYTHONDONTWRITEBYTECODE=1"

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
if /I "%~1"=="publish" goto publish
if /I "%~1"=="resume" goto publish
if /I "%~1"=="publish-self-test" goto publish
"%PYTHON%" "%~dp0candy_blog_page.py" %*
exit /b %ERRORLEVEL%

:publish
set "PYTHONUTF8=1"
set "PYTHONIOENCODING=utf-8"
"%PYTHON%" "%~dp0candy_category_publish.py" blog %*
exit /b %ERRORLEVEL%
