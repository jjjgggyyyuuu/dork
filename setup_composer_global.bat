@echo OFF
REM Move composer.phar to a globally accessible location and make it executable

REM Find PHP installation directory (optional, but good practice)
REM FOR /F "tokens=*" %%P IN ('where php') DO (
REM  SET PHP_DIR=%%~dpP
REM  GOTO FoundPHP
REM )
REM ECHO PHP not found in PATH. Cannot reliably determine directory for composer.
REM GOTO End

REM :FoundPHP
REM Simple approach: Place it in a common PATH directory like C:\Windows
REM Or better: Find the PHP directory and place it there.
REM Let's try placing it alongside PHP if possible

REM Simple approach for now: Move to a known directory and rename
REM Create a directory if it doesn't exist (e.g., C:\bin or use PHP dir)
REM For simplicity, let's try putting it directly in the PHP directory found earlier

REM Find PHP path again to be sure
FOR /F "tokens=*" %%P IN ('where php') DO (
    echo Moving composer.phar to %%~dpP
    move /Y composer.phar "%%~dpPcomposer.phar" > nul
    IF ERRORLEVEL 1 (
        echo Failed to move composer.phar. Trying C:\Windows...
        move /Y composer.phar C:\Windows\composer.phar > nul
        IF ERRORLEVEL 1 (
            echo Failed to move composer.phar to C:\Windows. Trying current directory link...
            GOTO CreateLink
        ) ELSE (
            echo Creating composer.bat in C:\Windows...
            echo @php "%%~dpPcomposer.phar" %%* > C:\Windows\composer.bat
            IF ERRORLEVEL 1 (
               echo Failed to create composer.bat in C:\Windows.
            ) ELSE (
               echo Successfully set up composer globally in C:\Windows.
            )
            GOTO End
        )
    ) ELSE (
        echo Creating composer.bat in %%~dpP
        echo @php "%%~dpPcomposer.phar" %%* > "%%~dpPcomposer.bat"
        IF ERRORLEVEL 1 (
            echo Failed to create composer.bat in %%~dpP.
        ) ELSE (
            echo Successfully set up composer globally alongside PHP.
        )
        GOTO End
    )
)

:CreateLink
REM Fallback: Create a batch file in the current directory to run composer.phar
echo @php "%~dp0composer.phar" %* > composer.bat
echo Could not move composer.phar globally. Created composer.bat in the current directory instead.
echo You may need to add this directory to your PATH or move composer.phar and composer.bat manually.
GOTO End

:End
pause 