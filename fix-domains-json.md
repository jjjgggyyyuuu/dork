# How to Fix the domains.json File

If you're seeing a "Property expected" error in your domains.json file, follow these steps to fix it:

## Option 1: Replace the file contents

1. Open the `out/api/domains.json` file in a text editor
2. Delete all contents
3. Copy and paste the following valid JSON:

```json
{
  "domains": [
    {
      "id": 1,
      "name": "example.com",
      "value": 2500,
      "category": "Business",
      "length": 11,
      "extension": ".com",
      "age": 5,
      "traffic": 1200,
      "relevance": 85,
      "brandability": 80,
      "potentialROI": 35
    },
    {
      "id": 2,
      "name": "domainmarket.net",
      "value": 1800,
      "category": "E-commerce",
      "length": 14,
      "extension": ".net",
      "age": 3,
      "traffic": 800,
      "relevance": 75,
      "brandability": 70,
      "potentialROI": 28
    },
    {
      "id": 3,
      "name": "techhub.io",
      "value": 3200,
      "category": "Technology",
      "length": 10,
      "extension": ".io",
      "age": 2,
      "traffic": 2000,
      "relevance": 90,
      "brandability": 85,
      "potentialROI": 42
    },
    {
      "id": 4,
      "name": "healthportal.org",
      "value": 1950,
      "category": "Healthcare",
      "length": 16,
      "extension": ".org",
      "age": 4,
      "traffic": 1500,
      "relevance": 80,
      "brandability": 75,
      "potentialROI": 30
    },
    {
      "id": 5,
      "name": "financetools.com",
      "value": 2800,
      "category": "Finance",
      "length": 13,
      "extension": ".com",
      "age": 6,
      "traffic": 1800,
      "relevance": 88,
      "brandability": 82,
      "potentialROI": 38
    }
  ]
}
```

4. Save the file

## Option 2: Run the updated build script

1. Delete the `out` directory
2. Run the updated `build-for-hostinger.bat` script

## Common JSON Syntax Errors to Watch Out For

1. **Missing or extra commas**: Every item in an array or object needs a comma after it, except the last one
2. **Trailing commas**: Don't put a comma after the last item in an array or object
3. **Missing quotes around property names**: All property names must be in double quotes
4. **Unmatched brackets**: Every `{` needs a matching `}` and every `[` needs a matching `]`
5. **Using single quotes**: JSON requires double quotes (`"`) for strings, not single quotes (`'`)

Once you've fixed the file, upload it to your Hostinger hosting along with all other files in the `out` directory. 