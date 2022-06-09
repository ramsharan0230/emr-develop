ENDPOINTS 
=========
`DEV => https://2019holidays.buddhaair.com/api/v1` <br>
<!--`LIVE => https://www.sastotickets.com/api/v1`  --> 

GENERAL APIS
===========


### BANNER
`ENDPOINT : ~/banner` <br>
`METHOD : GET` 

#### Success Response
```json
{
    "success": true,
    "message": "Homepage banner data.",
    "data": {
        "banner": [
            {
                "id": 2,
                "user_id": 1,
                "name": "Second Banner",
                "banner_type": null,
                "url": "http://localhost/holidays/public/frontend/images/banner1.jpg",
                "image": "1559033625-603648.jpg",
                "status": 1,
                "orderby": 1,
                "created_at": "2019-05-28 14:38:45",
                "updated_at": "2019-08-07 14:26:46"
            },
            {
                "id": 1,
                "user_id": 1,
                "name": "First banner",
                "banner_type": null,
                "url": "http://localhost/holidays/public/frontend/images/banner1.jpg",
                "image": "1559033596-675483.jpg",
                "status": 1,
                "orderby": 2,
                "created_at": "2019-05-28 14:38:15",
                "updated_at": "2019-08-07 14:26:46"
            }
        ]
    }
}
``` 

#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Banner data not found."
}
```

### COUNTRIES LIST FOR SEARCH
`ENDPOINT : ~/countries` <br>
`METHOD : GET` 

#### Success Response 
<a href="json/countries" target="_blank">Click here to view success response</a>


#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Country data not found."
}
```

### PACKAGE LIST FOR HOMEPAGE
`ENDPOINT : ~/package` <br>
`METHOD : GET` 

#### Success Response 
<a href="json/search-package" target="_blank">Click here to view success response</a>

#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Packages not found."
}
```

### PACKAGE LIST FOR BUDDHA AIR
`ENDPOINT : ~/packages-buddha-air` <br>
`METHOD : GET` 

#### Success Response 
<a href="json/search-package" target="_blank">Click here to view success response</a>

#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Packages not found."
}
```

DESTINATION API
============

### DESTINATION LIST FROM FOR SEARCH
`ENDPOINT : ~/destinations-from` <br>
`METHOD : GET` 

#### Success Response
```json
{ 
   "success":true,
   "message":"Homepage destinations data.",
   "data":{ 
      "destinations":[ 
         { 
            "id":2,
            "sectorname":"Kathmandu",
            "sectorcode":"KTM",
            "image":"1569913018-302668.jpg"
         },
         { 
            "id":8,
            "sectorname":"Vanarasi",
            "sectorcode":"VNS",
            "image":"1575449834-931077.jpg"
         }
      ]
   }
}
``` 

#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Destinations data not found."
}
```

### DESTINATION LIST
`ENDPOINT : ~/destinations` <br>
`METHOD : GET` 

#### Success Response
```json
{ 
   "success":true,
   "message":"Homepage destinations data.",
   "data":{ 
      "destinations":[ 
         { 
            "id":2,
            "sectorname":"Kathmandu",
            "sectorcode":"KTM",
            "image":"1569913018-302668.jpg"
         },
         { 
            "id":3,
            "sectorname":"Pokhara",
            "sectorcode":"PKR",
            "image":"1569912987-191927.jpg"
         },
         { 
            "id":4,
            "sectorname":"Chitwan",
            "sectorcode":"CHI",
            "image":"1569912951-406304.jpg"
         },
         { 
            "id":5,
            "sectorname":"Lumbini",
            "sectorcode":"LUM",
            "image":"1569912910-628383.jpg"
         },
         { 
            "id":6,
            "sectorname":"Nepalgunj",
            "sectorcode":"NPG",
            "image":"1569912875-628802.png"
         },
         { 
            "id":7,
            "sectorname":"Muktinath",
            "sectorcode":"MKT",
            "image":"1569912796-667682.jpg"
         },
         { 
            "id":8,
            "sectorname":"Vanarasi",
            "sectorcode":"VNS",
            "image":"1575449834-931077.jpg"
         }
      ]
   }
}
``` 

#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Destinations data not found."
}
```

### PACKAGE LIST BY DESTINATION
`ENDPOINT : ~/destinations/{destination-name}` <br>
`METHOD : GET` 

#### Success Response
<a href="json/destination-list" target="_blank">Click here to view success response</a>

#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Packages not found."
}
```

### DESTINATION TO LIST FOR SEARCH 
`ENDPOINT : ~/destinations-to/{sector_code}` <br>
`METHOD : GET`

`REQUEST PARAMETER`
`ATTRIBUTE`  | `DATATYPE` | `REQUIRED` | `DESCRIPTION` |`VALUE(EG.)`
------------ | ---------- | ---------- | ------------- | 
sector_code  |   string   |  required  | Code of city  | KTM

#### Success Response 
```
{
    {
        "success": true,
        "message": "Relevant destinations.",
        "data": [
            {
                "id": 5,
                "sectorname": "Lumbini",
                "sectorcode": "LUM",
                "image": "1569912910-628383.jpg"
            },
            {
                "id": 6,
                "sectorname": "Nepalgunj",
                "sectorcode": "NPG",
                "image": "1569912875-628802.png"
            },
            {
                "id": 7,
                "sectorname": "Muktinath",
                "sectorcode": "MKT",
                "image": "1569912796-667682.jpg"
            }
        ]
    }
}
```


#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Relevant destinations data not found."
}
```

SEARCH API
============

### PACKAGE SEARCH
`ENDPOINT : ~/search-destinations` <br>
`METHOD : POST` 

`REQUEST PARAMETER`
`ATTRIBUTE`  | `DATATYPE` | `REQUIRED` | `DESCRIPTION` |`VALUE(EG.)`
------------ | ---------- | ---------- | ------------- | 
destination_from  |   integer   |  required  | ID of city              | 1
destination_to  |   integer   |  required  | ID of city              | 1
trip_date  |   date   |  required  | Departure ```Format => Y-m-d``` | `2019-08-29`
country  |   integer   |  required  | country ID             | 1

#### Success Response 
<a href="json/search-package" target="_blank">Click here to view success response</a>

#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Packages not found."
}
```

### PACKAGE SEARCH BUDDHA AIR
`ENDPOINT : ~/holiday-buddha-air/search-package` <br>
`METHOD : POST` 

`REQUEST PARAMETER`
`ATTRIBUTE`  | `DATATYPE` | `REQUIRED` | `DESCRIPTION` |`VALUE(EG.)`
------------ | ---------- | ---------- | ------------- | 
destination_from  |   string   |  required  | Code of city              | KTM
destination_to  |   string   |  required  | Code of city              | MKT
trip_date  |   date   |  required  | Departure ```Format => Y-m-d``` | `2019-08-29`
country  |   string   |  required  | country Code            | NP

#### Success Response 


#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Packages not found."
}
```

### PACKAGE DETAILS
`ENDPOINT : ~/package-detail/{id}/{nationality}` <br>
`METHOD : GET` 

#### Success Response 
<a href="json/package-details" target="_blank">Click here to view success response</a>

#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Package detail not found."
}
```

BOOKING API
============

### PACKAGE BOOKING
`ENDPOINT : ~/book-package` <br>
`METHOD : POST` 

`REQUEST PARAMETER`
`ATTRIBUTE`  | `DATATYPE` | `REQUIRED` | `DESCRIPTION` |`VALUE(EG.)`
------------ | ---------- | ---------- | ------------- | 
package_id  |   integer   |  required  | ID of city | 1
booking_key  |   string   |  required  | Booking Key | aa8ef470-df5a-11e9-a89c-b156e92813d8
booking_date  |   date   |  required  | Departure ```Format => yy-mm-dd``` | `2019-08-23`
first_name  |   string   |  required  | First Name | John
middle_name  |   string   |  optional  | Middle Name | 1
last_name  |   string   |  required  | Last Name | Doe
phone_number  |   integer   |  required  | Phone Number | 9876543210
country  |   integer   |  required  | Country Code | 123
email  |   string   |  required  | Email | test@test.com
room  |   array   |  required  | Array of details of pax in each room | ```"room":[{"roomNum":1,"adult":1,"child":{"childnum":0,"child_category":[]}},{"roomNum":2,"adult":1,"child":{"childnum":2,"child_category":["infant","child_without_bed"]}}```



#### Success Response 
```json
{ 
   "success":true,
   "message":"Booking Created Successfully.",
   "data":{ 
      "bookingKey":"9b5d4600-1657-11ea-96ec-73dbb62b7ba8",
      "total_cost":460,
      "grand_total_cost":"504.21",
      "total_extra_cost":0,
      "currency":"NPR"
   }
}
```

#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Cannot create Booking."
}
```

### PACKAGE MOBILE LOG
`ENDPOINT : ~/create-mobile-log` <br>
`METHOD : POST` 

`REQUEST PARAMETER`
`ATTRIBUTE`  | `DATATYPE` | `REQUIRED` | `DESCRIPTION` |`VALUE(EG.)`
------------ | ---------- | ---------- | ------------- | 
booking_key  |   string   |  required  | Booking Key | aa8ef470-df5a-11e9-a89c-b156e92813d8
payment_method  |   string   |  required  | Method | checkout_request
payment_mode  |   string   |  required  | Mode of payment | esewa, nabil_pay
payload  |   array   |  required  | Array of all data | 



#### Success Response 
```json
{
    "success": true,
    "message": "Log created successfully.",
    "data": "Success"
}
```

#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Log cannot be created."
}
```

ESEWA SUCCESS API
============

### UPDATE BOOKING STATUS
`ENDPOINT : ~/pay-esewa-success` <br>
`METHOD : POST` 

`REQUEST PARAMETER`
`ATTRIBUTE`  | `DATATYPE` | `REQUIRED` | `DESCRIPTION` |`VALUE(EG.)`
------------ | ---------- | ---------- | ------------- | -----------
booking_key  |   string   |  required  | Booking Key | 311cb3e0-013f-11ea-a732-67b84818dae1
reference_id  |   string   |  required  | Reference key | 00006TH
product_id  |   string   |  required  | Product id | BH-M-311cb3e0-013f-11ea-a732-67b84818dae1
total_amount  |   string   |  required  | Amount Paid | 1000
payment_response  |   string   |  required  | Payment Response | '{"productId":"311cb3e0-013f-11ea-a732-67b84818dae1","productName":"Android SDK Payment","totalAmount":"100.0","environment":"test","code":"00","merchantName":"Android SDK Payment","message":{"technicalSuccessMessage":"Your transaction has been completed.","successMessage":"Your transaction has been completed."},"transactionDetails":{"status":"COMPLETE","referenceId":"00006TM","date":"Thu Dec 12 14:44:24 GMT+05:45 2019"}}'


#### Success Response 
```json
{
    "success": true,
    "message": "Thank you for booking.",
    "data": {
        "booking_key": "1dfc358r0-1ccd-11ea-9f7d-4768d1188f16",
        "customer": {
            "name": "binay  thapa",
            "contact_number": "89801387906",
            "email": "thapa.binay111@gmail.com",
            "nationality": "Nepal"
        },
        "package": {
            "name": "Neque quibusdam ulla",
            "amount": "72.60"
        },
        "ticket_url": "http://10.13.210.32/buddha-holidays/public/frontend/pdf/tickets/BH-191212-158.pdf"
    }
}
```

#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Cannot find Booking / generate ticket"
}
```


DOWNLOAD TICKET API
============

### TICKET GENERATE
`ENDPOINT : ~/download-ticket` <br>
`METHOD : POST` 

`REQUEST PARAMETER`
`ATTRIBUTE`  | `DATATYPE` | `REQUIRED` | `DESCRIPTION` |`VALUE(EG.)`
------------ | ---------- | ---------- | ------------- | -----------
booking_key  |   string   |  required  | Booking Key | aa8ef470-df5a-11e9-a89c-b156e92813d8


#### Success Response 

```json{
    "success": true,
    "message": "Ticket Url",
    "data": {
        "booking_key": "1dfc3580-1ccd-11ea-9f7d-4768d1188f16",
        "ticket_url": "http://10.13.210.32/buddha-holidays/public/frontend/pdf/tickets/BH-191212-158.pdf"
    }
}
```

#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Cannot find Booking / generate ticket"
}
```

BUDDHA AIR PROMOTION
============

### PROMOTION PACKAGES
`ENDPOINT : ~/destination-promotion` <br>
`METHOD : POST` 

`REQUEST PARAMETER`
`ATTRIBUTE`  | `DATATYPE` | `REQUIRED` | `DESCRIPTION` |`VALUE(EG.)`
------------ | ---------- | ---------- | ------------- | -----------
destination_from  |   string   |  required  | Destination Code | KTM
destination_to  |   string   |  required  | Destination Code | PKR
currency  |   string   |  required  | Destination Code | NPR


#### Success Response 

<a href="json/package-promotion" target="_blank">Click here to view success response</a>

#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Packages not found."
}
```

HBL PAYMENT
============

### PAYMENT
`ENDPOINT : ~/hbl/payment/request` <br>
`METHOD : POST` 

`REQUEST PARAMETER`
`ATTRIBUTE`  | `DATATYPE` | `REQUIRED` | `DESCRIPTION` |`VALUE(EG.)`
------------ | ---------- | ---------- | ------------- | -----------
booking_code  |   string   |  required  | Booking Code | da83d210-89e9-11ea-a26b-4b2c3552e295
amount  |   float   |  required  | Booking Amount | 5000.00
currency_code  |   string   |  required  | Currency Code | NPR
type  |   string   |  required  | Type | F
devicetype  |   string   |  required  | Device Type | android
device_id  |   string   |  required  | Device ID | 9cf465d030710487


#### Success Response 

```json
{
    "success": true,
    "message": "Success",
    "data": {
        "description": " da83d210-89e9-11ea-a26b-4b2c3552e295",
        "Order_code": "da83d210-89e9-11ea-a26b-4b2c3552e295_5ea95b2329c16",
        "Amount": "000005000.00",
        "CurrencyCode": 840,
        "request_url": "https://hblpgw.2c2p.com/HBLPGW/Payment/Payment/Payment",
        "paymentGatewayId": "9103337457",
        "hashValue": "2109E752EA52FC92DCAB087F5DAF8A6046B53C1F762CA4A4B664F4871BA737B5"
    }
}
```

#### Error Response
```json
{
    "success": false,
    "message": "Not Found",
    "data": "Something went wrong, please try again."
}
```
SEND QUERY API
============

### PACKAGE QUERY
`ENDPOINT : ~/send-package-query` <br>
`METHOD : POST` 

`REQUEST PARAMETER`
`ATTRIBUTE`  | `DATATYPE` | `REQUIRED` | `DESCRIPTION` |`VALUE(EG.)`
------------ | ---------- | ---------- | ------------- | 
name  |   string   |  required  | First Name | John Doe
email  |   string   |  required  | Email | test@test.com
package_id  |   integer   |  required  | ID of city | 1
booking_key  |   string   |  required  | Booking Key | aa8ef470-df5a-11e9-a89c-b156e92813d8
package_link  |   string   |  required  | link | https://2019holidays.buddhaair.com/package-list/mountains-breakfast
nationality_id  |   integer   |  required  | Country Code | 123
message  |   string   |  required  | Message | Lorem ipsum dolor sit amet, consectetur adipiscing elit.
no_pax  |   integer   |  required  | Passenger count | 2
destination  |   string   |  required  | Destination | MOUNTAINS & BREAKFAST
travel_date  |   date   |  required  | Departure ```Format => yy-mm-dd``` | `2019-08-23`

#### Success Response 
```json
{ 
   "success": false,
   "message": "Query successfully submitted, Thank You."
}
```

#### Error Response
```json
{
    "success": false,
    "message": "Message could not be sent. Something went wrong."
}
```
