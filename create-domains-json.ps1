# PowerShell script to create domains.json file
Write-Host "Creating domains.json file..."

# Create domains json content
$domains = @{
    domains = @(
        @{
            id = 1
            name = "example.com"
            value = 2500
            category = "Business"
            length = 11
            extension = ".com"
            age = 5
            traffic = 1200
            relevance = 85
            brandability = 80
            potentialROI = 35
        },
        @{
            id = 2
            name = "domainmarket.net"
            value = 1800
            category = "E-commerce"
            length = 14
            extension = ".net"
            age = 3
            traffic = 800
            relevance = 75
            brandability = 70
            potentialROI = 28
        },
        @{
            id = 3
            name = "techhub.io"
            value = 3200
            category = "Technology"
            length = 10
            extension = ".io"
            age = 2
            traffic = 2000
            relevance = 90
            brandability = 85
            potentialROI = 42
        },
        @{
            id = 4
            name = "healthportal.org"
            value = 1950
            category = "Healthcare"
            length = 16
            extension = ".org"
            age = 4
            traffic = 1500
            relevance = 80
            brandability = 75
            potentialROI = 30
        },
        @{
            id = 5
            name = "financetools.com"
            value = 2800
            category = "Finance"
            length = 13
            extension = ".com"
            age = 6
            traffic = 1800
            relevance = 88
            brandability = 82
            potentialROI = 38
        }
    )
}

# Make sure out/api directory exists
if (!(Test-Path "out/api")) {
    New-Item -ItemType Directory -Path "out/api" -Force
}

# Convert to JSON and write to file
$domains | ConvertTo-Json -Depth 4 | Out-File -FilePath "out/api/domains.json" -Encoding UTF8

Write-Host "domains.json file created successfully!" 