@echo off
setlocal
set "PYTHON=%LOCALAPPDATA%\Programs\Python\Python312\python.exe"
if exist "%PYTHON%" goto run
set "PYTHON=python"
:run
if /I "%~1"=="publish-next" goto publish
if /I "%~1"=="publish" goto publish
if /I "%~1"=="publish-self-test" goto publish
if /I "%~1"=="resume" goto publish
"%PYTHON%" "%~dp0candy_area_page.py" %*
exit /b %ERRORLEVEL%

:publish
set "PYTHONUTF8=1"
set "PYTHONIOENCODING=utf-8"
"%PYTHON%" "%~dp0candy_area_publish.py" %*
exit /b %ERRORLEVEL%
