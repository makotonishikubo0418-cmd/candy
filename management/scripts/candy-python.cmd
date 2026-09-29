@echo off
setlocal
set "PYTHONDONTWRITEBYTECODE=1"
set "PYTHONUTF8=1"
set "PYTHONIOENCODING=utf-8"
set "GIT_OPTIONAL_LOCKS=0"
set "BUNDLED_GIT=%USERPROFILE%\.cache\codex-runtimes\codex-primary-runtime\dependencies\native\git\cmd"
if exist "%BUNDLED_GIT%\git.exe" set "PATH=%BUNDLED_GIT%;%PATH%"
set "CANDY_PROBE=import sys; assert sys.version_info >= (3,12)"
if /I "%~1"=="hotel" set "CANDY_PROBE=import sys; assert sys.version_info >= (3,12); from PIL import Image"
if /I "%~1"=="area" set "CANDY_PROBE=import sys; assert sys.version_info >= (3,12); from PIL import Image"
set "CANDY_PYTHON=%LOCALAPPDATA%\Programs\Python\Python312\python.exe"
if not exist "%CANDY_PYTHON%" goto bundled
"%CANDY_PYTHON%" -B -c "%CANDY_PROBE%" >nul 2>nul
if not errorlevel 1 goto run
:bundled
set "CANDY_PYTHON=%USERPROFILE%\.cache\codex-runtimes\codex-primary-runtime\dependencies\python\python.exe"
if not exist "%CANDY_PYTHON%" goto path_python
"%CANDY_PYTHON%" -B -c "%CANDY_PROBE%" >nul 2>nul
if not errorlevel 1 goto run
:path_python
set "CANDY_PYTHON=python"
"%CANDY_PYTHON%" -B -c "%CANDY_PROBE%" >nul 2>nul
if not errorlevel 1 goto run
py -3 -B -c "%CANDY_PROBE%" >nul 2>nul
if errorlevel 1 goto unavailable
py -3 -B "%~dp0candy_tool.py" %*
exit /b %ERRORLEVEL%
:run
"%CANDY_PYTHON%" -B "%~dp0candy_tool.py" %*
exit /b %ERRORLEVEL%
:unavailable
echo RESULT=STOP Python 3.12+ is required; area/hotel tools also require Pillow. No compatible installed runtime found. 1>&2
exit /b 2
