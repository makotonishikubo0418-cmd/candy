@echo off
setlocal
set "PYTHON=%LOCALAPPDATA%\Programs\Python\Python312\python.exe"
if exist "%PYTHON%" goto run
set "PYTHON=python"
:run
"%PYTHON%" "%~dp0candy_area_page.py" %*
exit /b %ERRORLEVEL%
