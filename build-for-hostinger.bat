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

:: Create domains.json file
echo Creating domains.json static data...
(
echo {
echo   "domains": [
echo     {
echo       "id": 1,
echo       "name": "example.com",
echo       "value": 2500,
echo       "category": "Business",
echo       "length": 11,
echo       "extension": ".com",
echo       "age": 5,
echo       "traffic": 1200,
echo       "relevance": 85,
echo       "brandability": 80,
echo       "potentialROI": 35
echo     },
echo     {
echo       "id": 2,
echo       "name": "domainmarket.net",
echo       "value": 1800,
echo       "category": "E-commerce",
echo       "length": 14,
echo       "extension": ".net",
echo       "age": 3,
echo       "traffic": 800,
echo       "relevance": 75,
echo       "brandability": 70,
echo       "potentialROI": 28
echo     },
echo     {
echo       "id": 3,
echo       "name": "techhub.io",
echo       "value": 3200,
echo       "category": "Technology",
echo       "length": 10,
echo       "extension": ".io",
echo       "age": 2,
echo       "traffic": 2000,
echo       "relevance": 90,
echo       "brandability": 85,
echo       "potentialROI": 42
echo     },
echo     {
echo       "id": 4,
echo       "name": "healthportal.org",
echo       "value": 1950,
echo       "category": "Healthcare",
echo       "length": 16,
echo       "extension": ".org",
echo       "age": 4,
echo       "traffic": 1500,
echo       "relevance": 80,
echo       "brandability": 75,
echo       "potentialROI": 30
echo     },
echo     {
echo       "id": 5,
echo       "name": "financetools.com",
echo       "value": 2800,
echo       "category": "Finance",
echo       "length": 13,
echo       "extension": ".com",
echo       "age": 6,
echo       "traffic": 1800,
echo       "relevance": 88,
echo       "brandability": 82,
echo       "potentialROI": 38
echo     }
echo   ]
echo }
) > out\api\domains.json

:: Copy .htaccess file
echo Copying .htaccess file...
copy static.htaccess out\.htaccess

echo Build complete! Upload the contents of the 'out' folder to your Hostinger hosting.
echo.
echo IMPORTANT: Make sure to include the .htaccess file when uploading.
pause 