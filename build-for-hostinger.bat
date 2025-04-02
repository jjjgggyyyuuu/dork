@echo off
echo Building static site for Hostinger...

:: Install dependencies
echo Installing dependencies...
call npm install

:: Build the Next.js static site
echo Building static site...
call npm run build

:: Create API directory
echo Creating API directory...
mkdir out\api

echo Select method to create domains.json:
echo 1. Use batch commands
echo 2. Use PowerShell script
echo 3. Use prepared fixed-domains.json file
set /p method="Enter choice (1-3): "

if "%method%"=="1" (
    :: Create domains.json file using batch commands
    echo Creating domains.json with batch commands...
    echo { > out\api\domains.json
    echo   "domains": [ >> out\api\domains.json
    echo     { >> out\api\domains.json
    echo       "id": 1, >> out\api\domains.json
    echo       "name": "example.com", >> out\api\domains.json
    echo       "value": 2500, >> out\api\domains.json
    echo       "category": "Business", >> out\api\domains.json
    echo       "length": 11, >> out\api\domains.json
    echo       "extension": ".com", >> out\api\domains.json
    echo       "age": 5, >> out\api\domains.json
    echo       "traffic": 1200, >> out\api\domains.json
    echo       "relevance": 85, >> out\api\domains.json
    echo       "brandability": 80, >> out\api\domains.json
    echo       "potentialROI": 35 >> out\api\domains.json
    echo     }, >> out\api\domains.json
    echo     { >> out\api\domains.json
    echo       "id": 2, >> out\api\domains.json
    echo       "name": "domainmarket.net", >> out\api\domains.json
    echo       "value": 1800, >> out\api\domains.json
    echo       "category": "E-commerce", >> out\api\domains.json
    echo       "length": 14, >> out\api\domains.json
    echo       "extension": ".net", >> out\api\domains.json
    echo       "age": 3, >> out\api\domains.json
    echo       "traffic": 800, >> out\api\domains.json
    echo       "relevance": 75, >> out\api\domains.json
    echo       "brandability": 70, >> out\api\domains.json
    echo       "potentialROI": 28 >> out\api\domains.json
    echo     }, >> out\api\domains.json
    echo     { >> out\api\domains.json
    echo       "id": 3, >> out\api\domains.json
    echo       "name": "techhub.io", >> out\api\domains.json
    echo       "value": 3200, >> out\api\domains.json
    echo       "category": "Technology", >> out\api\domains.json
    echo       "length": 10, >> out\api\domains.json
    echo       "extension": ".io", >> out\api\domains.json
    echo       "age": 2, >> out\api\domains.json
    echo       "traffic": 2000, >> out\api\domains.json
    echo       "relevance": 90, >> out\api\domains.json
    echo       "brandability": 85, >> out\api\domains.json
    echo       "potentialROI": 42 >> out\api\domains.json
    echo     }, >> out\api\domains.json
    echo     { >> out\api\domains.json
    echo       "id": 4, >> out\api\domains.json
    echo       "name": "healthportal.org", >> out\api\domains.json
    echo       "value": 1950, >> out\api\domains.json
    echo       "category": "Healthcare", >> out\api\domains.json
    echo       "length": 16, >> out\api\domains.json
    echo       "extension": ".org", >> out\api\domains.json
    echo       "age": 4, >> out\api\domains.json
    echo       "traffic": 1500, >> out\api\domains.json
    echo       "relevance": 80, >> out\api\domains.json
    echo       "brandability": 75, >> out\api\domains.json
    echo       "potentialROI": 30 >> out\api\domains.json
    echo     }, >> out\api\domains.json
    echo     { >> out\api\domains.json
    echo       "id": 5, >> out\api\domains.json
    echo       "name": "financetools.com", >> out\api\domains.json
    echo       "value": 2800, >> out\api\domains.json
    echo       "category": "Finance", >> out\api\domains.json
    echo       "length": 13, >> out\api\domains.json
    echo       "extension": ".com", >> out\api\domains.json
    echo       "age": 6, >> out\api\domains.json
    echo       "traffic": 1800, >> out\api\domains.json
    echo       "relevance": 88, >> out\api\domains.json
    echo       "brandability": 82, >> out\api\domains.json
    echo       "potentialROI": 38 >> out\api\domains.json
    echo     } >> out\api\domains.json
    echo   ] >> out\api\domains.json
    echo } >> out\api\domains.json
) else if "%method%"=="2" (
    :: Use PowerShell script
    echo Creating domains.json with PowerShell...
    powershell -ExecutionPolicy Bypass -File create-domains-json.ps1
) else if "%method%"=="3" (
    :: Copy from prepared file
    echo Copying prepared domains.json file...
    copy fixed-domains.json out\api\domains.json
) else (
    echo Invalid choice, using method 3 (fixed file)...
    copy fixed-domains.json out\api\domains.json
)

:: Copy .htaccess file
echo Copying .htaccess file...
copy static.htaccess out\.htaccess

echo Build complete! Upload the contents of the 'out' folder to your Hostinger hosting.
echo.
echo IMPORTANT: Make sure to include the .htaccess file when uploading.
pause 