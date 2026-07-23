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
if /I "%~1"=="direct-check" goto targetgate
if /I "%~1"=="legacy-check" goto textmigration
if /I "%~1"=="legacy-convert" goto textmigration
if /I "%~1"=="legacy-self-test" goto textmigration
if /I "%~1"=="image-plan" goto hotelimage
if /I "%~1"=="image-render" goto hotelimage
if /I "%~1"=="image-check" goto hotelimage
if /I "%~1"=="image-self-test" goto hotelimage
if /I "%~1"=="audit-inputs" goto targetgate
if /I "%~1"=="audit-existing" goto targetgate
"%PYTHON%" "%~dp0candy_hotel_page.py" %*
exit /b %ERRORLEVEL%

:targetgate
set "PYTHONUTF8=1"
set "PYTHONIOENCODING=utf-8"
"%PYTHON%" "%~dp0candy_hotel_target_gate.py" %*
exit /b %ERRORLEVEL%

:textmigration
set "PYTHONUTF8=1"
set "PYTHONIOENCODING=utf-8"
"%PYTHON%" "%~dp0candy_hotel_text_migration.py" %*
exit /b %ERRORLEVEL%

:hotelimage
set "PYTHONUTF8=1"
set "PYTHONIOENCODING=utf-8"
"%PYTHON%" "%~dp0candy_hotel_image.py" %*
exit /b %ERRORLEVEL%

:publish
set "PYTHONUTF8=1"
set "PYTHONIOENCODING=utf-8"
"%PYTHON%" "%~dp0candy_hotel_publish.py" %*
exit /b %ERRORLEVEL%
