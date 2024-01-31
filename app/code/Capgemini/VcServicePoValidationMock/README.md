VC Service PO Validation Mock
===

A mock API service to use for testing integration with VC PO number validation service
while the final service is still being developed.

* The mock service validates that the appKey, accountNumber and poNumber exist. If not, the service returns a 404 error.
* The mock service returns true for all POs ending with a number, false for all other POs.
* The mock service allows anonymous requests.
* The mock service returns a 401 response if the appKey is empty.

**Magento API POST endpoint**: /V1/vcmock/povalidation

**Sample request**
```json
[
    {
        "appKey": "TestAPIKey123",
        "accountNumber": "testaccount12345",
        "poNumber": "testpo12345"
    }
]
```

**Sample response**
```json
[
    {
        "poNumber": "testpo1234",
        "isValidPo": true
    }
]
```
