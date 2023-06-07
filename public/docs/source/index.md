---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.

<!-- END_INFO -->

#Authentication


<!-- START_c3fa189a6c95ca36ad6ac4791a873d23 -->
## Sign in
user login

> Example request:

```bash
curl -X POST "http://flexibledrive.localhost.com/api/login" \
    -H "Content-Type: application/json" \
    -d '{"email":"john.does@test.com","password":"johnDeo@123"}'

```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/login");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "email": "john.does@test.com",
    "password": "johnDeo@123"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/login`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | String |  required  | user registerd email address
    password | String |  required  | user password

<!-- END_c3fa189a6c95ca36ad6ac4791a873d23 -->

<!-- START_90f45d502fd52fdc0b289e55ba3c2ec6 -->
## Sign up
user registration

> Example request:

```bash
curl -X POST "http://flexibledrive.localhost.com/api/signup" \
    -H "Content-Type: application/json" \
    -d '{"first_name":"john","last_name":"doe","company_name":"test company","address_line1":"test adress1","address_line2":"test adress2","state":"ACT","zip":"123456","mobile":1234567890,"email":"john.does@test.com","password":"johnDeo@123","confirm_password":"johnDeo@123"}'

```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/signup");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "first_name": "john",
    "last_name": "doe",
    "company_name": "test company",
    "address_line1": "test adress1",
    "address_line2": "test adress2",
    "state": "ACT",
    "zip": "123456",
    "mobile": 1234567890,
    "email": "john.does@test.com",
    "password": "johnDeo@123",
    "confirm_password": "johnDeo@123"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/signup`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    first_name | String |  required  | First Name
    last_name | String |  required  | Last Name
    company_name | String |  required  | Company 
    address_line1 | String |  required  | Address Line1
    address_line2 | String |  required  | Address Line2
    state | String |  required  | ACT, NSW, NT, QLD, SA, TAS, VIC, WA
    zip | String |  required  | Pincode (Zipcode) 
    mobile | number |  required  | phone number 
    email | String |  required  | Email address
    password | String |  required  | password String required password having atlist 1 uppercase, 1 lowercase , 1 number, 1 symbol and minimum 8 character long. 
    confirm_password | String |  required  | Confirm Password

<!-- END_90f45d502fd52fdc0b289e55ba3c2ec6 -->

<!-- START_00e7e21641f05de650dbe13f242c6f2c -->
## Sign out

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/logout" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/logout");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "status": 0,
    "message": "Unauthorized!"
}
```

### HTTP Request
`GET api/logout`


<!-- END_00e7e21641f05de650dbe13f242c6f2c -->

#Banner Managemant


<!-- START_0167daff8996fda2b72f0a5425d66b94 -->
## Banner

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/banner?page=17&per_page=9&paginate=voluptas" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/banner");

    let params = {
            "page": "17",
            "per_page": "9",
            "paginate": "voluptas",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 13,
                "image": "Screenshot_from_2019-11-01_11-15-48_1572944697.jpg",
                "created_at": "2019-11-05 09:04:57",
                "image_url": "http:\/\/flexibledrive.localhost.com\/storage\/banners\/Screenshot_1572944697.jpg"
            }
        ],
        "first_page_url": "http:\/\/flexibledrive.localhost.com\/api\/banner?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/flexibledrive.localhost.com\/api\/banner?page=1",
        "next_page_url": null,
        "path": "http:\/\/flexibledrive.localhost.com\/api\/banner",
        "per_page": 15,
        "prev_page_url": null,
        "to": 3,
        "total": 3
    },
    "message": "Banners"
}
```
> Example response (401):

```json
{
    "status": 0,
    "message": "Unauthorized!"
}
```

### HTTP Request
`GET api/banner`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    page |  optional  | (Integer) page number
    per_page |  optional  | (Integer) items per page
    paginate |  optional  | (bool) whather data arranged by pagination or not

<!-- END_0167daff8996fda2b72f0a5425d66b94 -->

#Brands


<!-- START_49314ee162f7e839596684af0fed40e9 -->
## Get all brands
This API will be use for get all brands and products count.

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/brands?make_id=99&model_id=353&sub_model=2.0+i&year=1985&chassis_code=69%2CAF3%2C78&engine_code=B201&cc=1985&power=81&body_type=Hatchback&rego_number=99&state=SA&vin_number=JF1BL5KS57G03135" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/brands");

    let params = {
            "make_id": "99",
            "model_id": "353",
            "sub_model": "2.0 i",
            "year": "1985",
            "chassis_code": "69,AF3,78",
            "engine_code": "B201",
            "cc": "1985",
            "power": "81",
            "body_type": "Hatchback",
            "rego_number": "99",
            "state": "SA",
            "vin_number": "JF1BL5KS57G03135",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": [
        {
            "id": 153,
            "name": "REMSA",
            "logo": "https:\/\/imageapi.partsdb.com.au\/api\/Image?urlId=%2F0E9mNHbJEo6NhpoCE61T2KGglsoQwHJUqdd%2FHSOxgGXF6BNaAZa4FrzNpTPVO4X",
            "product_count": 0
        },
        {
            "id": 161,
            "name": "TRW",
            "logo": "https:\/\/imageapi.partsdb.com.au\/api\/Image?urlId=rUUDyKFr8bwEleK4DJYPXZfNmfUUzXe9oRCDM9s0%2Bh60LzoEb8C6kLe9K%2FCrc7D4",
            "product_count": 0
        },
        {
            "id": 4585,
            "name": "DOGA",
            "logo": "https:\/\/imageapi.partsdb.com.au\/api\/Image?urlId=4y8xgqBFcwiSj5Pgv5fA2XPcfvvdBTUXJHkozQ747W%2F9A5xrU6R8BYPobXkLy47m",
            "product_count": 0
        },
        {
            "id": 6370,
            "name": "Flexible Drive",
            "logo": "",
            "product_count": 0
        },
        {
            "id": 10178,
            "name": "PARts Demo",
            "logo": "",
            "product_count": 0
        }
    ],
    "message": "All brands"
}
```

### HTTP Request
`GET api/brands`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    make_id |  optional  | (Integer) Make ID ( Table : "makes" )
    model_id |  optional  | (Integer) Model ID ( Table : "models" )
    sub_model |  optional  | (String) car sub model
    year |  optional  | (Integer) Year
    chassis_code |  optional  | (String) car chassis code
    engine_code |  optional  | (String) car engine code  
    cc |  optional  | (String) car engine cc 
    power |  optional  | (String) car engine power 
    body_type |  optional  | (String) car body type
    rego_number |  optional  | (String) vehicle Rego Number
    state |  optional  | (String) ACT, NSW, NT, QLD, SA, TAS, VIC, WA
    vin_number |  optional  | (String) VIN Number

<!-- END_49314ee162f7e839596684af0fed40e9 -->

#Cart


<!-- START_4d939b7a0fc250ea3bd1c9afbaabcbfa -->
## Add to Cart
This API will add producrs into the cart.

> Example request:

```bash
curl -X POST "http://flexibledrive.localhost.com/api/cart/add/product" \
    -H "Content-Type: application/json" \
    -d '{"products":"1","qty":"2"}'

```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/cart/add/product");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "products": "1",
    "qty": "2"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/cart/add/product`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    products | Integer |  required  | Product ID ( Table : "products" )
    qty | Integer |  required  | Product Quantitiy

<!-- END_4d939b7a0fc250ea3bd1c9afbaabcbfa -->

<!-- START_e1658903dc246ad338f90911b577eab2 -->
## All Cart Items
Get all items added to cart.

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/cart/items" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/cart/items");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "items": [
            {
                "id": 130,
                "product_id": 20,
                "user_id": 11,
                "qty": 3,
                "product": {
                    "id": 20,
                    "brand_id": 153,
                    "product_nr": "0006.91",
                    "name": "Brake Pad Set",
                    "description": "Brake Pad Set",
                    "corresponding_part_number": null,
                    "price_nett": 15,
                    "price_retail": 20,
                    "fitting_position": "Front Axle",
                    "length": null,
                    "height": null,
                    "thickness": null,
                    "weight": null,
                    "standard_description_id": "402",
                    "brand": {
                        "id": 153,
                        "name": "REMSA",
                        "logo": "https:\/\/imageapi.partsdb.com.au\/api\/Image?urlId=%2F0E9mNHbJEo6NhpoCE61T2KGglsoQwHJUqdd%2FHSOxgGXF6BNaAZa4FrzNpTPVO4X"
                    },
                    "vehicles": [],
                    "images": [
                        {
                            "id": 16,
                            "product_id": 20,
                            "image": "0006-91-MODEL.BMP",
                            "image_url": "http:\/\/35.164.124.177\/fd-backend\/public\/storage\/products\/0006-91-MODEL.BMP"
                        }
                    ],
                    "categories": []
                }
            }
        ],
        "delivery_charges": 10,
        "GST": 4.5,
        "subtotal": 45,
        "total": 59.5,
        "pickup_locations": [
            {
                "id": 1,
                "name": "Flexible Drive Victoria",
                "address": "86 Stubbs Street",
                "city": "Kensington",
                "state": "VIC",
                "post_code": "3031",
                "phone": "+61 3 9381 9222",
                "email": "vicsales@flexibledrive.com.au",
                "contact": "James Ferry",
                "mobile": "0419 009 086",
                "contact_email": "jferry@flexibledrive.com.au"
            }
        ]
    },
    "message": "cart items"
}
```
> Example response (401):

```json
{
    "status": 0,
    "message": "Unauthorized!"
}
```

### HTTP Request
`GET api/cart/items`


<!-- END_e1658903dc246ad338f90911b577eab2 -->

<!-- START_4ae65571e2931cc12303e43663dbe9d4 -->
## Remove item from cart
This API will remove items from cart. Product id need to be passed Ie. (/cart/11/remove)

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/cart/1/remove" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/cart/1/remove");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "status": 0,
    "message": "Unauthorized!"
}
```

### HTTP Request
`GET api/cart/{product_id}/remove`


<!-- END_4ae65571e2931cc12303e43663dbe9d4 -->

#Categories


<!-- START_109013899e0bc43247b0f00b67f889cf -->
## Get all Categories
This API will be use for get all Categories and products count.

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/categories?make_id=99&model_id=353&sub_model=2.0+i&year=1985&chassis_code=69%2CAF3%2C78&engine_code=B201&cc=1985&power=81&body_type=Hatchback&rego_number=99&state=SA&vin_number=JF1BL5KS57G03135" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/categories");

    let params = {
            "make_id": "99",
            "model_id": "353",
            "sub_model": "2.0 i",
            "year": "1985",
            "chassis_code": "69,AF3,78",
            "engine_code": "B201",
            "cc": "1985",
            "power": "81",
            "body_type": "Hatchback",
            "rego_number": "99",
            "state": "SA",
            "vin_number": "JF1BL5KS57G03135",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "parent_id": "0",
            "name": "Brake Systems",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 3,
            "parent_id": "0",
            "name": "Wipers",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 5,
            "parent_id": "0",
            "name": "Automotive Cables",
            "description": "Accelerator, Brake, Clutch (ABC) and Gearshift cables for light vehicle applications.",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 6,
            "parent_id": "5",
            "name": "Accelerator Cables",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 7,
            "parent_id": "5",
            "name": "Clutch Cables",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 8,
            "parent_id": "5",
            "name": "Brake Cables",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 9,
            "parent_id": "5",
            "name": "Gear Selector Cables",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 10,
            "parent_id": "5",
            "name": "Bonnet & Hatch Cables",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 11,
            "parent_id": "5",
            "name": "Kickdown Cables",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 12,
            "parent_id": "5",
            "name": "Speedometer Cables",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 13,
            "parent_id": "1",
            "name": "Brake Hydraulics",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 14,
            "parent_id": "1",
            "name": "Brake Pads",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 15,
            "parent_id": "1",
            "name": "Brake Rotors",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 16,
            "parent_id": "1",
            "name": "Wear Sensors",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 17,
            "parent_id": "1",
            "name": "Brake Shoes",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 18,
            "parent_id": "1",
            "name": "Brake Drums",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 19,
            "parent_id": "13",
            "name": "Master Cylinder",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 20,
            "parent_id": "0",
            "name": "Clutch Hydraulics",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 21,
            "parent_id": "13",
            "name": "Wheel Cylinders",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 22,
            "parent_id": "20",
            "name": "Master Cylinder",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 23,
            "parent_id": "20",
            "name": "Slave Cylinder",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        },
        {
            "id": 24,
            "parent_id": "13",
            "name": "Hoses",
            "description": "",
            "icon": null,
            "image": null,
            "product_count": 0
        }
    ],
    "message": "All Categories"
}
```

### HTTP Request
`GET api/categories`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    make_id |  optional  | (Integer) Make ID ( Table : "makes" )
    model_id |  optional  | (Integer) Model ID ( Table : "models" )
    sub_model |  optional  | (String) car sub model
    year |  optional  | (Integer) Year
    chassis_code |  optional  | (String) car chassis code
    engine_code |  optional  | (String) car engine code  
    cc |  optional  | (String) car engine cc 
    power |  optional  | (String) car engine power 
    body_type |  optional  | (String) car body type
    rego_number |  optional  | (String) vehicle Rego Number
    state |  optional  | (String) ACT, NSW, NT, QLD, SA, TAS, VIC, WA
    vin_number |  optional  | (String) VIN Number

<!-- END_109013899e0bc43247b0f00b67f889cf -->

#Contact


<!-- START_30a7ad44c4383c85c240df8f76cd8c54 -->
## Contact Form
This API send contact enquiry to the admin.

> Example request:

```bash
curl -X POST "http://flexibledrive.localhost.com/api/contact" \
    -H "Content-Type: application/json" \
    -d '{"first_name":"John","last_name":"Deo","email":"john.deo@test.com","message":"Test Enquiry"}'

```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/contact");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "first_name": "John",
    "last_name": "Deo",
    "email": "john.deo@test.com",
    "message": "Test Enquiry"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/contact`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    first_name | String |  required  | First Name
    last_name | String |  required  | Last Name
    email | String |  required  | Email Address 
    message | String |  required  | Message 

<!-- END_30a7ad44c4383c85c240df8f76cd8c54 -->

#Forgot Password


<!-- START_924f19de8b2936f12f0313aecb472c36 -->
## Reset Password
This API will send verification token to registerd email address.

> Example request:

```bash
curl -X POST "http://flexibledrive.localhost.com/api/reset/password/request" \
    -H "Content-Type: application/json" \
    -d '{"email":"john.deo@test.com"}'

```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/reset/password/request");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "email": "john.deo@test.com"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/reset/password/request`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    email | String |  required  | Email Address.

<!-- END_924f19de8b2936f12f0313aecb472c36 -->

<!-- START_9f04fe8090248157467c01fde75448f9 -->
## Verify Token
This EMail will verify token from email address.

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/reset/password/1/verify" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/reset/password/1/verify");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "success": false,
    "message": "This password reset token is invalid"
}
```

### HTTP Request
`GET api/reset/password/{token}/verify`


<!-- END_9f04fe8090248157467c01fde75448f9 -->

<!-- START_63d11c72408e2261d4d36cb6c741553d -->
## Set New Password
This API will set new password

> Example request:

```bash
curl -X POST "http://flexibledrive.localhost.com/api/reset/password/update" \
    -H "Content-Type: application/json" \
    -d '{"password":"johnDeo@123","token":"sit"}'

```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/reset/password/update");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "password": "johnDeo@123",
    "token": "sit"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/reset/password/update`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    password | String |  required  | password having atlist 1 uppercase, 1 lowercase , 1 number, 1 symbol and minimum 8 character long. 
    token | String |  required  | token from email address

<!-- END_63d11c72408e2261d4d36cb6c741553d -->

#Market Intel


<!-- START_bb112f89316a03ece00e1ccf1633b68d -->
## Maket Intel

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/market-intel?page=13&per_page=12&paginate=officiis" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/market-intel");

    let params = {
            "page": "13",
            "per_page": "12",
            "paginate": "officiis",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 1,
                "title": "test blog demo test",
                "blog_slug": "test-blog-demo-test",
                "description": "<p>test<\/p>",
                "url": null,
                "image": "maxresdefault_1572864536.jpg",
                "created_at": "2019-11-04 10:48:56",
                "updated_at": "2019-11-04 10:48:56",
                "deleted_at": null,
                "image_url": "http:\/\/flexibledrive.localhost.com\/storage\/marketintel\/maxresdefault_1572864536.jpg"
            }
        ],
        "first_page_url": "http:\/\/flexibledrive.localhost.com\/api\/market-intel?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/flexibledrive.localhost.com\/api\/market-intel?page=1",
        "next_page_url": null,
        "path": "http:\/\/flexibledrive.localhost.com\/api\/market-intel",
        "per_page": 15,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    },
    "message": "Market intels"
}
```
> Example response (401):

```json
{
    "status": 0,
    "message": "Unauthorized!"
}
```

### HTTP Request
`GET api/market-intel`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    page |  optional  | (Integer) page number
    per_page |  optional  | (Integer) items per page
    paginate |  optional  | (bool) whather data arranged by pagination or not

<!-- END_bb112f89316a03ece00e1ccf1633b68d -->

<!-- START_bf54ae906eb420f90b5e3cd4cbc48b4b -->
## Market Intel Detail

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/market-intel/1?slug=debitis" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/market-intel/1");

    let params = {
            "slug": "debitis",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "test blog demo test",
        "blog_slug": "test-blog-demo-test",
        "description": "<p>test<\/p>",
        "url": null,
        "image": "maxresdefault_1572864536.jpg",
        "created_at": "2019-11-04 10:48:56",
        "updated_at": "2019-11-04 10:48:56",
        "deleted_at": null,
        "image_url": "http:\/\/flexibledrive.localhost.com\/storage\/marketintel\/maxresdefault_1572864536.jpg"
    },
    "message": "Market intel detail"
}
```
> Example response (401):

```json
{
    "status": 0,
    "message": "Unauthorized!"
}
```

### HTTP Request
`GET api/market-intel/{slug}`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    slug |  required  | (String) slug of wich stored in blog_slug column  (Table:market_intels)

<!-- END_bf54ae906eb420f90b5e3cd4cbc48b4b -->

#Order


<!-- START_a688dfaa2caddd5442cbf4524ca11051 -->
## Place Order
This APi will place order and send email to user and admin with PDF attechment.

> Example request:

```bash
curl -X POST "http://flexibledrive.localhost.com/api/cart/placeorder" \
    -H "Content-Type: application/json" \
    -d '{"delivery_method":"1","first_name":"John","last_name":"Deo","mobile":"Deo","date":"2019-12-12","time":"10:00","location":"1","address_line1":"test address line1","address_line2":"test address line2","state":"ACT","postal_code":"1234","email":"1234","products":"[1,2,3,4]"}'

```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/cart/placeorder");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "delivery_method": "1",
    "first_name": "John",
    "last_name": "Deo",
    "mobile": "Deo",
    "date": "2019-12-12",
    "time": "10:00",
    "location": "1",
    "address_line1": "test address line1",
    "address_line2": "test address line2",
    "state": "ACT",
    "postal_code": "1234",
    "email": "1234",
    "products": "[1,2,3,4]"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 88,
        "user_id": 8,
        "order_number": "FD0000088",
        "subtotal": 27247,
        "gst": 2724.7,
        "delivery": 10,
        "total": 29981.7,
        "status": 0,
        "reference_number": null,
        "repeat_order": 0,
        "delivery_method": "1",
        "invoice": "invoice_FD0000088.pdf",
        "created_at": "2019-10-22 11:23:07",
        "status_label": "Submitted",
        "delivery_type": "Delivery"
    },
    "message": "Thank you for submitting the order. You will shortly receive a confirmation email."
}
```

### HTTP Request
`POST api/cart/placeorder`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    delivery_method | Integer |  required  | delivery methods like 1 - Delivery, 2 - Pickup, 3 - Delivery & Pickup
    first_name | String |  required  | first name of user
    last_name | String |  required  | last name of user
    mobile | String |  required  | contact number of user
    date | Date |  optional  | Only required if user seledted pickup method
    time | Time |  optional  | Only required if user seledted pickup method
    location | String |  optional  | Only required if user selected pickup method. pass id of pickup_location ( Table : "pickup_locations" )
    address_line1 | String |  optional  | user asddress Only required if user selected delievry method 
    address_line2 | String |  optional  | user asddress Only required if user selected delievry method 
    state | String |  optional  | user state. Only required if user selected delievry method 
    postal_code | String |  optional  | user Postal code (ZIP code) Only required if user selected delievry method 
    email | String |  optional  | user email address. Only required if user selected delievry method 
    products | Interger |  optional  | miltiple product it Array

<!-- END_a688dfaa2caddd5442cbf4524ca11051 -->

<!-- START_f9301c03a9281c0847565f96e6f723de -->
## All Orders
This API will be use for user order list and also for seaech order by ord er by number, and also for get order for particualr date duration.

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/orders?page=3&per_page=8&from_date=doloribus&to_date=dolores&order_number=4" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/orders");

    let params = {
            "page": "3",
            "per_page": "8",
            "from_date": "doloribus",
            "to_date": "dolores",
            "order_number": "4",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 62,
                "user_id": 8,
                "order_number": "FD0000062",
                "subtotal": 27236,
                "gst": 2723.6,
                "delivery": 10,
                "total": 29969.6,
                "status": 4,
                "reference_number": null,
                "repeat_order": 0,
                "delivery_method": "3",
                "invoice": "invoice_FD0000062.pdf",
                "created_at": "2019-10-18 10:30:12",
                "status_label": "Cancelled",
                "status_badge": "<label class=\"badge badge-danger\">Cancelled<\/label>",
                "delivery_type": "Pick Up & Delivery",
                "items": [
                    {
                        "id": 47,
                        "product_id": 13625,
                        "order_id": 62,
                        "qty": 1,
                        "price": 13625,
                        "total": 13625,
                        "delivery_address_id": 61,
                        "pickup_address_id": 0,
                        "order_status": null,
                        "product": {
                            "id": 13625,
                            "brand_id": 10178,
                            "product_nr": "SYN0W16",
                            "name": "20° Piston Position Template",
                            "description": "20° Piston Position Template",
                            "corresponding_part_number": null,
                            "price_nett": 13625,
                            "price_retail": 0,
                            "fitting_position": null,
                            "length": null,
                            "height": null,
                            "thickness": null,
                            "weight": null,
                            "standard_description_id": "2364",
                            "brand": {
                                "id": 10178,
                                "name": "PARts Demo",
                                "logo": ""
                            },
                            "vehicles": [
                                {
                                    "id": 16454,
                                    "make_id": 111,
                                    "model_id": 681,
                                    "year_from": 2011,
                                    "year_to": 2017,
                                    "year_range": "09\/11-09\/17",
                                    "sub_model": "2.5 Hybrid (AVV50_)",
                                    "chassis_code": "AVV50",
                                    "engine_code": null,
                                    "cc": "2494",
                                    "power": "118",
                                    "body_type": "Sedan",
                                    "brake_system": null,
                                    "pivot": {
                                        "product_id": 13625,
                                        "vehicle_id": 16454
                                    },
                                    "make": {
                                        "id": 111,
                                        "name": "TOYOTA"
                                    },
                                    "model": {
                                        "id": 681,
                                        "make_id": 111,
                                        "name": "CAMRY"
                                    }
                                }
                            ],
                            "images": [],
                            "categories": []
                        },
                        "delivery": {
                            "id": 61,
                            "user_id": 8,
                            "first_name": "krutik",
                            "last_name": "patel",
                            "company_name": null,
                            "address_line1": "test",
                            "address_line2": "test",
                            "state": "VIC",
                            "zip": "1234",
                            "mobile": "1234567890",
                            "email": "kkrutik@gmail.com"
                        },
                        "pickup": null
                    },
                    {
                        "id": 48,
                        "product_id": 13611,
                        "order_id": 62,
                        "qty": 1,
                        "price": 13611,
                        "total": 13611,
                        "delivery_address_id": 0,
                        "pickup_address_id": 10,
                        "order_status": null,
                        "product": {
                            "id": 13611,
                            "brand_id": 6370,
                            "product_nr": "BA135",
                            "name": "Accelerator Cable",
                            "description": "Accelerator Cable",
                            "corresponding_part_number": null,
                            "price_nett": 13611,
                            "price_retail": 0,
                            "fitting_position": null,
                            "length": null,
                            "height": null,
                            "thickness": null,
                            "weight": null,
                            "standard_description_id": "618",
                            "brand": {
                                "id": 6370,
                                "name": "Flexible Drive",
                                "logo": ""
                            },
                            "vehicles": [
                                {
                                    "id": 43230,
                                    "make_id": 20,
                                    "model_id": 2470,
                                    "year_from": 1965,
                                    "year_to": 1967,
                                    "year_range": "03\/65-03\/67",
                                    "sub_model": "3.7",
                                    "chassis_code": "AP6",
                                    "engine_code": null,
                                    "cc": "3697",
                                    "power": "109",
                                    "body_type": "Sedan",
                                    "brake_system": null,
                                    "pivot": {
                                        "product_id": 13611,
                                        "vehicle_id": 43230
                                    },
                                    "make": {
                                        "id": 20,
                                        "name": "CHRYSLER"
                                    },
                                    "model": {
                                        "id": 2470,
                                        "make_id": 20,
                                        "name": "VALIANT"
                                    }
                                }
                            ],
                            "images": [],
                            "categories": [
                                {
                                    "id": 6,
                                    "parent_id": "5",
                                    "name": "Accelerator Cables",
                                    "description": "",
                                    "icon": null,
                                    "image": null,
                                    "pivot": {
                                        "product_id": 13611,
                                        "category_id": 6
                                    }
                                }
                            ]
                        },
                        "delivery": null,
                        "pickup": {
                            "id": 10,
                            "user_id": 8,
                            "pickup_location_id": 1,
                            "first_name": "krutik",
                            "last_name": "patel",
                            "mobile": "2345678999",
                            "pickup_date_time": "2019-10-20 01:15:00",
                            "location": {
                                "id": 1,
                                "name": "Flexible Drive Victoria",
                                "address": "86 Stubbs Street",
                                "city": "Kensington",
                                "state": "VIC",
                                "post_code": "3031",
                                "phone": "+61 3 9381 9222",
                                "email": "vicsales@flexibledrive.com.au",
                                "contact": "James Ferry",
                                "mobile": "0419 009 086",
                                "contact_email": "jferry@flexibledrive.com.au"
                            }
                        }
                    }
                ]
            }
        ],
        "first_page_url": "http:\/\/flexibledrive.localhost.com\/api\/orders?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/flexibledrive.localhost.com\/api\/orders?page=1",
        "next_page_url": null,
        "path": "http:\/\/flexibledrive.localhost.com\/api\/orders",
        "per_page": 20,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    },
    "message": "Your Orders!"
}
```
> Example response (401):

```json
{
    "status": 0,
    "message": "Unauthorized!"
}
```

### HTTP Request
`GET api/orders`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    page |  optional  | (Integer) page number
    per_page |  optional  | (Integer) items per page
    from_date |  optional  | (Date)  order from date you want to filter
    to_date |  optional  | (Date)  order to date you want to filter
    order_number |  optional  | (String) order number

<!-- END_f9301c03a9281c0847565f96e6f723de -->

<!-- START_5c4a92da17adeab1b9d4d04048ee8b72 -->
## Reference Number
add refrence number to order.

> Example request:

```bash
curl -X PUT "http://flexibledrive.localhost.com/api/order/reference-number/1" \
    -H "Content-Type: application/json" \
    -d '{"order_number":"FD000001","reference_number":"FD000001"}'

```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/order/reference-number/1");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "order_number": "FD000001",
    "reference_number": "FD000001"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 87,
        "user_id": 8,
        "order_number": "FD0000087",
        "subtotal": 6,
        "gst": 0.6,
        "delivery": 10,
        "total": 16.6,
        "status": 0,
        "reference_number": "zssdasdasd",
        "repeat_order": 0,
        "delivery_method": "1",
        "invoice": "invoice_FD0000087.pdf",
        "created_at": "2019-10-21 13:13:21",
        "status_label": "Submitted",
        "delivery_type": "Delivery"
    },
    "message": "Reference number updated to order!"
}
```

### HTTP Request
`PUT api/order/reference-number/{id}`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    order_number | String |  required  | order number  ( Table : "orders" ) 
    reference_number | String |  optional  | user will add his refrence number 

<!-- END_5c4a92da17adeab1b9d4d04048ee8b72 -->

<!-- START_3d316058224d3ffc8d041528835f6335 -->
## Delete Order

> Example request:

```bash
curl -X DELETE "http://flexibledrive.localhost.com/api/order/1/delete?order_id=1" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/order/1/delete");

    let params = {
            "order_id": "1",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 67,
        "user_id": 8,
        "order_number": "FD0000067",
        "subtotal": 20737,
        "gst": 2073.7,
        "delivery": 10,
        "total": 22820.7,
        "status": 0,
        "reference_number": null,
        "repeat_order": 0,
        "delivery_method": "2",
        "invoice": "invoice_FD0000067.pdf",
        "created_at": "2019-10-18 12:16:59",
        "status_label": "Submitted",
        "delivery_type": "Pick Up",
        "items": [
            {
                "id": 63,
                "product_id": 4864,
                "order_id": 67,
                "qty": 1,
                "price": 4864,
                "total": 4864,
                "delivery_address_id": 0,
                "pickup_address_id": 14,
                "order_status": null,
                "product": {
                    "id": 4864,
                    "brand_id": 153,
                    "product_nr": "62036.10",
                    "name": "Brake Disc Rotor",
                    "description": "Brake Disc Rotor",
                    "corresponding_part_number": null,
                    "price_nett": 4864,
                    "price_retail": 0,
                    "fitting_position": "Rear Axle",
                    "length": null,
                    "height": null,
                    "thickness": null,
                    "weight": null,
                    "standard_description_id": "82"
                }
            }
        ]
    },
    "message": "Order Deleted!"
}
```

### HTTP Request
`DELETE api/order/{order_id}/delete`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    order_id |  required  | Interger (Tabale: orders)

<!-- END_3d316058224d3ffc8d041528835f6335 -->

<!-- START_e4df2ba9107c962b757c0379a81ec475 -->
## Bulk Order Delete

> Example request:

```bash
curl -X POST "http://flexibledrive.localhost.com/api/order/bulk/delete" \
    -H "Content-Type: application/json" \
    -d '{"orders":"[1,2]"}'

```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/order/bulk/delete");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "orders": "[1,2]"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": [],
    "message": "Order Deleted!"
}
```

### HTTP Request
`POST api/order/bulk/delete`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    orders | required |  optional  | order id Array. array elements Interger (Tabale: orders)

<!-- END_e4df2ba9107c962b757c0379a81ec475 -->

<!-- START_f9f224430a2eb2839b4b3fe3e0f0d449 -->
## Export order

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/order/export?orders=suscipit" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/order/export");

    let params = {
            "orders": "suscipit",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "download_url": "http:\/\/flexibledrive.localhost.com\/storage\/invoices\/1571748692.pdf"
    },
    "message": "Invoices Link"
}
```
> Example response (401):

```json
{
    "status": 0,
    "message": "Unauthorized!"
}
```

### HTTP Request
`GET api/order/export`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    orders |  required  | (Array) Order ID , items (Integer)  ( Table : "orders" )

<!-- END_f9f224430a2eb2839b4b3fe3e0f0d449 -->

<!-- START_889af22504b51549b062bd730d8b11b1 -->
## Cancel Order

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/order/1/cancel?order_id=1" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/order/1/cancel");

    let params = {
            "order_id": "1",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 59,
        "user_id": 8,
        "order_number": "FD0000059",
        "subtotal": 14,
        "gst": 1.4,
        "delivery": 10,
        "total": 25.4,
        "status": 4,
        "reference_number": null,
        "repeat_order": 0,
        "delivery_method": "1",
        "invoice": "invoice_FD0000059.pdf",
        "created_at": "2019-10-17 15:05:39",
        "status_label": "Cancelled",
        "delivery_type": "Delivery",
        "items": [
            {
                "id": 42,
                "product_id": 2,
                "order_id": 59,
                "qty": 2,
                "price": 2,
                "total": 4,
                "delivery_address_id": 59,
                "pickup_address_id": 0,
                "order_status": null,
                "product": {
                    "id": 2,
                    "brand_id": 153,
                    "product_nr": "0002.00",
                    "name": "Brake Pad Set",
                    "description": "Brake Pad Set",
                    "corresponding_part_number": null,
                    "price_nett": 2,
                    "price_retail": 0,
                    "fitting_position": "Front Axle",
                    "length": null,
                    "height": null,
                    "thickness": null,
                    "weight": null,
                    "standard_description_id": "402"
                }
            },
            {
                "id": 43,
                "product_id": 5,
                "order_id": 59,
                "qty": 2,
                "price": 5,
                "total": 10,
                "delivery_address_id": 0,
                "pickup_address_id": 0,
                "order_status": null,
                "product": {
                    "id": 5,
                    "brand_id": 153,
                    "product_nr": "0002.30",
                    "name": "Brake Pad Set",
                    "description": "Brake Pad Set",
                    "corresponding_part_number": null,
                    "price_nett": 5,
                    "price_retail": 0,
                    "fitting_position": "Front Axle",
                    "length": null,
                    "height": null,
                    "thickness": null,
                    "weight": null,
                    "standard_description_id": "402"
                }
            }
        ]
    },
    "message": "Order Cancelled!"
}
```
> Example response (401):

```json
{
    "status": 0,
    "message": "Unauthorized!"
}
```

### HTTP Request
`GET api/order/{order_id}/cancel`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    order_id |  required  | Interger (Tabale: orders)

<!-- END_889af22504b51549b062bd730d8b11b1 -->

<!-- START_009e5836c4c515cbf02ad445486f8606 -->
## Favourite Orders

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/order/favourite?page=11&per_page=16" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/order/favourite");

    let params = {
            "page": "11",
            "per_page": "16",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "current_page": 1,
        "data": [
            {
                "id": 4,
                "user_id": 8,
                "order_id": 60,
                "order": {
                    "id": 60,
                    "user_id": 8,
                    "order_number": "FD0000060",
                    "subtotal": 21,
                    "gst": 2.1,
                    "delivery": 10,
                    "total": 33.1,
                    "status": 2,
                    "reference_number": null,
                    "repeat_order": 0,
                    "delivery_method": "2",
                    "invoice": "invoice_FD0000060.pdf",
                    "created_at": "2019-10-18 04:42:15",
                    "status_label": "Delivering",
                    "status_badge": "<label class=\"badge badge-info\">Delivering<\/label>",
                    "delivery_type": "Pick Up",
                    "invoice_url": "http:\/\/virtualhost.com\/storage\/invoices\/invoice_FD0000060.pdf",
                    "user": {
                        "id": 8,
                        "first_name": "Krutik",
                        "last_name": "Patel",
                        "email": "kkrutikk@gmail.com",
                        "email_verified_at": null,
                        "account_code": "FD002",
                        "company_name": "Test Company",
                        "address_line1": "New Test Street",
                        "address_line2": "Gandhinagar",
                        "state": "Test State",
                        "zip": "1224",
                        "profile_image": "npDRw6Smaa.jpg",
                        "mobile": "1234567890",
                        "admin_approval_status": 2,
                        "created_at": "2019-09-27 08:53:57",
                        "updated_at": "2019-10-22 10:09:11",
                        "image_url": "http:\/\/flexibledrive.localhost.com\/storage\/users\/npDRw6Smaa.jpg",
                        "name": "Krutik Patel"
                    },
                    "items": [
                        {
                            "id": 44,
                            "product_id": 5,
                            "order_id": 60,
                            "qty": 3,
                            "price": 5,
                            "total": 15,
                            "delivery_address_id": 0,
                            "pickup_address_id": 9,
                            "order_status": null,
                            "product": {
                                "id": 5,
                                "brand_id": 153,
                                "product_nr": "0002.30",
                                "name": "Brake Pad Set",
                                "description": "Brake Pad Set",
                                "corresponding_part_number": null,
                                "price_nett": 10,
                                "price_retail": 0,
                                "fitting_position": "Front Axle",
                                "length": null,
                                "height": null,
                                "thickness": null,
                                "weight": null,
                                "standard_description_id": "402",
                                "brand": {
                                    "id": 153,
                                    "name": "REMSA",
                                    "logo": ""
                                },
                                "vehicles": [
                                    {
                                        "id": 311,
                                        "make_id": 92,
                                        "model_id": 359,
                                        "year_from": 1984,
                                        "year_to": 1987,
                                        "year_range": "02\/84-06\/87",
                                        "sub_model": "3.2 SC Carrera",
                                        "chassis_code": null,
                                        "engine_code": null,
                                        "cc": "3164",
                                        "power": "152",
                                        "body_type": "Coupe",
                                        "brake_system": "Hydraulic",
                                        "pivot": {
                                            "product_id": 5,
                                            "vehicle_id": 311
                                        },
                                        "make": {
                                            "id": 92,
                                            "name": "PORSCHE"
                                        },
                                        "model": {
                                            "id": 359,
                                            "make_id": 92,
                                            "name": "911"
                                        }
                                    }
                                ],
                                "images": [],
                                "categories": []
                            }
                        }
                    ]
                }
            }
        ],
        "first_page_url": "http:\/\/flexibledrive.localhost.com\/api\/orders\/favourite?page=1",
        "from": 1,
        "last_page": 1,
        "last_page_url": "http:\/\/flexibledrive.localhost.com\/api\/orders\/favourite?page=1",
        "next_page_url": null,
        "path": "http:\/\/flexibledrive.localhost.com\/api\/orders\/favourite",
        "per_page": 20,
        "prev_page_url": null,
        "to": 1,
        "total": 1
    },
    "message": "favourite orders."
}
```
> Example response (401):

```json
{
    "status": 0,
    "message": "Unauthorized!"
}
```

### HTTP Request
`GET api/order/favourite`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    page |  optional  | (Integer) page number
    per_page |  optional  | (Integer) items per page

<!-- END_009e5836c4c515cbf02ad445486f8606 -->

<!-- START_0d092ea95638fe0a8d88d7032a7419b8 -->
## Add Order to favourite

> Example request:

```bash
curl -X POST "http://flexibledrive.localhost.com/api/order/favourite" \
    -H "Content-Type: application/json" \
    -d '{"order_id":"144"}'

```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/order/favourite");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "order_id": "144"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "order_id": 60,
        "user_id": 8,
        "id": 3
    },
    "message": "Order added to favourite"
}
```

### HTTP Request
`POST api/order/favourite`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    order_id | Integer |  required  | Order Id (Table : orders)

<!-- END_0d092ea95638fe0a8d88d7032a7419b8 -->

<!-- START_b7b9a447bf82278c6dddd399397f1a3e -->
## Remove from favourite

> Example request:

```bash
curl -X DELETE "http://flexibledrive.localhost.com/api/order/favourite/1" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/order/favourite/1");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "DELETE",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": [],
    "message": "Order removed from favourite"
}
```

### HTTP Request
`DELETE api/order/favourite/{order_id}`


<!-- END_b7b9a447bf82278c6dddd399397f1a3e -->

#Products


<!-- START_86e0ac5d4f8ce9853bc22fd08f2a0109 -->
## All Products
This API will be use for product catalogue, quick search , advance search.

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/products?page=19&per_page=11&brand_id=nihil&category_id=ea&products=omnis&product_nr=17&make_id=99&model_id=353&sub_model=2.0+i&year=1985&chassis_code=69%2CAF3%2C78&engine_code=B201&cc=1985&power=81&body_type=Hatchback&rego_number=99&state=SA&vin_number=JF1BL5KS57G03135&sort_column=price_nett&sort_order=ASC" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/products");

    let params = {
            "page": "19",
            "per_page": "11",
            "brand_id": "nihil",
            "category_id": "ea",
            "products": "omnis",
            "product_nr": "17",
            "make_id": "99",
            "model_id": "353",
            "sub_model": "2.0 i",
            "year": "1985",
            "chassis_code": "69,AF3,78",
            "engine_code": "B201",
            "cc": "1985",
            "power": "81",
            "body_type": "Hatchback",
            "rego_number": "99",
            "state": "SA",
            "vin_number": "JF1BL5KS57G03135",
            "sort_column": "price_nett",
            "sort_order": "ASC",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "success": false,
    "message": "Invalid input",
    "data": [
        "The brand id must be an integer.",
        "The category id must be an integer.",
        "The products must be an array."
    ]
}
```

### HTTP Request
`GET api/products`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    page |  optional  | (Integer) page number
    per_page |  optional  | (Integer) items per page
    brand_id |  optional  | (Integer) Brand ID ( Table : "brands" )
    category_id |  optional  | (Integer) Category ID ( Table : "categories" )
    products |  optional  | (Array) Product ID , items (Integer)  ( Table : "products" )
    product_nr |  optional  | (String) ProductNr (number)
    make_id |  optional  | (Integer) Make ID ( Table : "makes" )
    model_id |  optional  | (Integer) Model ID ( Table : "models" )
    sub_model |  optional  | (String) car sub model
    year |  optional  | (Integer) Year
    chassis_code |  optional  | (String) car chassis code
    engine_code |  optional  | (String) car engine code  
    cc |  optional  | (String) car engine cc 
    power |  optional  | (String) car engine power 
    body_type |  optional  | (String) car body type
    rego_number |  optional  | (String) vehicle Rego Number
    state |  optional  | String (ACT, NSW, NT, QLD, SA, TAS, VIC, WA)
    vin_number |  optional  | (String) VIN Number
    sort_column |  optional  | (String) sort product list liek Price , name etc.
    sort_order |  optional  | (String) sort by assanding or Desancding.

<!-- END_86e0ac5d4f8ce9853bc22fd08f2a0109 -->

<!-- START_8467efa5c5527721103d40adc52944d2 -->
## Product Detail
For get particular product detail. here need to pass product id ie (api/product/10/detail).

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/product/1/detail" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/product/1/detail");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 1,
        "brand_id": 153,
        "product_nr": "0001.02",
        "name": "Brake Pad Set",
        "description": "Brake Pad Set",
        "corresponding_part_number": "",
        "price_nett": 10,
        "price_retail": 0,
        "fitting_position": "Front Axle",
        "length": null,
        "height": null,
        "thickness": null,
        "weight": null,
        "standard_description_id": "402",
        "brand": {
            "id": 153,
            "name": "REMSA",
            "logo": "https:\/\/imageapi.partsdb.com.au\/api\/Image?urlId=%2F0E9mNHbJEo6NhpoCE61T2KGglsoQwHJUqdd%2FHSOxgGXF6BNaAZa4FrzNpTPVO4X"
        },
        "vehicles": [
            {
                "id": 3250,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1981,
                "year_to": 1985,
                "year_range": "06\/81-10\/85",
                "sub_model": "1.8",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "1796",
                "power": "55",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3250
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 3251,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1982,
                "year_to": 1985,
                "year_range": "01\/82-10\/85",
                "sub_model": "1.8",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "1796",
                "power": "60",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3251
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 3252,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1980,
                "year_to": 1985,
                "year_range": "11\/80-12\/85",
                "sub_model": "2.0",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "1971",
                "power": "71",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3252
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 3253,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1985,
                "year_to": 1988,
                "year_range": "09\/85-02\/88",
                "sub_model": "2.0",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "1971",
                "power": "72",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3253
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 3254,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1979,
                "year_to": 1986,
                "year_range": "08\/79-10\/86",
                "sub_model": "2.0 TI,STI",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "1995",
                "power": "81",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3254
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 3255,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1984,
                "year_to": 1988,
                "year_range": "03\/84-10\/88",
                "sub_model": "2.2 Turbo Injection",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "2155",
                "power": "114",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3255
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 3256,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1986,
                "year_to": 1993,
                "year_range": "03\/86-12\/93",
                "sub_model": "2.2",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "2165",
                "power": "84",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3256
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 3257,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1983,
                "year_to": 1993,
                "year_range": "03\/83-08\/93",
                "sub_model": "2.2",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "2165",
                "power": "85",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3257
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 3258,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1983,
                "year_to": 1993,
                "year_range": "08\/83-12\/93",
                "sub_model": "2.2 GTI",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "2165",
                "power": "90",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3258
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 3259,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1986,
                "year_to": 1993,
                "year_range": "06\/86-12\/93",
                "sub_model": "2.8 GTI V6",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "2849",
                "power": "105",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3259
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 3260,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1980,
                "year_to": 1986,
                "year_range": "09\/80-01\/86",
                "sub_model": "2.3 Turbo Diesel",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "2304",
                "power": "59",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3260
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 3261,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1981,
                "year_to": 1993,
                "year_range": "06\/81-12\/93",
                "sub_model": "2.5 Diesel",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "2498",
                "power": "55",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3261
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 6798,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1985,
                "year_to": 1996,
                "year_range": "04\/85-10\/96",
                "sub_model": "2.5 Turbo Diesel",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "2498",
                "power": "70",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 6798
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 6799,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1983,
                "year_to": 1993,
                "year_range": "08\/83-12\/93",
                "sub_model": "2.2 GTI",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "2165",
                "power": "96",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 6799
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 17628,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1987,
                "year_to": 1988,
                "year_range": "06\/87-12\/88",
                "sub_model": "2.2 Turbo Injection",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "2155",
                "power": "128",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 17628
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 23517,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1979,
                "year_to": 1982,
                "year_range": "05\/79-09\/82",
                "sub_model": "2.3 D",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "2304",
                "power": "51",
                "body_type": "Sedan",
                "brake_system": null,
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 23517
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 31831,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1985,
                "year_to": 1986,
                "year_range": "09\/85-10\/86",
                "sub_model": "1.8",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "1796",
                "power": "62",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 31831
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 31832,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1986,
                "year_to": 1987,
                "year_range": "01\/86-12\/87",
                "sub_model": "2.2 Turbo Injection",
                "chassis_code": "551A",
                "engine_code": null,
                "cc": "2155",
                "power": "110",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 31832
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 3264,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1983,
                "year_to": 1985,
                "year_range": "08\/83-12\/85",
                "sub_model": "2.0",
                "chassis_code": "551D",
                "engine_code": null,
                "cc": "1971",
                "power": "69",
                "body_type": "Wagon",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3264
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 3265,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1986,
                "year_to": 1993,
                "year_range": "01\/86-12\/93",
                "sub_model": "2.2",
                "chassis_code": "551D",
                "engine_code": null,
                "cc": "2165",
                "power": "84",
                "body_type": "Wagon",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3265
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 5825,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1985,
                "year_to": 1987,
                "year_range": "09\/85-11\/87",
                "sub_model": "2.0",
                "chassis_code": "551D",
                "engine_code": null,
                "cc": "1971",
                "power": "72",
                "body_type": "Wagon",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 5825
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 31833,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1982,
                "year_to": 1987,
                "year_range": "04\/82-11\/87",
                "sub_model": "2.0",
                "chassis_code": "551D",
                "engine_code": null,
                "cc": "1971",
                "power": "60",
                "body_type": "Wagon",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 31833
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 31834,
                "make_id": 88,
                "model_id": 277,
                "year_from": 1985,
                "year_to": 1986,
                "year_range": "09\/85-12\/86",
                "sub_model": "2.2 GTI",
                "chassis_code": "551D",
                "engine_code": null,
                "cc": "2165",
                "power": "90",
                "body_type": "Wagon",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 31834
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 277,
                    "make_id": 88,
                    "name": "505"
                }
            },
            {
                "id": 3249,
                "make_id": 88,
                "model_id": 300,
                "year_from": 1977,
                "year_to": 1983,
                "year_range": "08\/77-05\/83",
                "sub_model": "2.7 TI,STI",
                "chassis_code": "561A",
                "engine_code": null,
                "cc": "2664",
                "power": "106",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3249
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 300,
                    "make_id": 88,
                    "name": "604"
                }
            },
            {
                "id": 31837,
                "make_id": 88,
                "model_id": 300,
                "year_from": 1978,
                "year_to": 1982,
                "year_range": "04\/78-06\/82",
                "sub_model": "2.7 SL",
                "chassis_code": "561A",
                "engine_code": null,
                "cc": "2664",
                "power": "100",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 31837
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 300,
                    "make_id": 88,
                    "name": "604"
                }
            },
            {
                "id": 3247,
                "make_id": 88,
                "model_id": 276,
                "year_from": 1971,
                "year_to": 1986,
                "year_range": "03\/71-06\/86",
                "sub_model": "2.0 (A1, A13, MY1, MY3)",
                "chassis_code": "A,M",
                "engine_code": null,
                "cc": "1971",
                "power": "71",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3247
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 276,
                    "make_id": 88,
                    "name": "504"
                }
            },
            {
                "id": 3248,
                "make_id": 88,
                "model_id": 276,
                "year_from": 1971,
                "year_to": 1983,
                "year_range": "01\/71-06\/83",
                "sub_model": "2.0 TI (A12, A14)",
                "chassis_code": "A,M",
                "engine_code": null,
                "cc": "1971",
                "power": "78",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 3248
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 276,
                    "make_id": 88,
                    "name": "504"
                }
            },
            {
                "id": 31826,
                "make_id": 88,
                "model_id": 276,
                "year_from": 1975,
                "year_to": 1983,
                "year_range": "07\/75-12\/83",
                "sub_model": "2.3 D (A40, A45)",
                "chassis_code": "A,M",
                "engine_code": null,
                "cc": "2304",
                "power": "51",
                "body_type": "Sedan",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 31826
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 276,
                    "make_id": 88,
                    "name": "504"
                }
            },
            {
                "id": 2268,
                "make_id": 5,
                "model_id": 8,
                "year_from": 1991,
                "year_to": 1994,
                "year_range": "09\/91-07\/94",
                "sub_model": "S4 Turbo quattro",
                "chassis_code": "C4,4A5",
                "engine_code": null,
                "cc": "2226",
                "power": "169",
                "body_type": "Wagon",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 2268
                },
                "make": {
                    "id": 5,
                    "name": "AUDI"
                },
                "model": {
                    "id": 8,
                    "make_id": 5,
                    "name": "100"
                }
            },
            {
                "id": 31828,
                "make_id": 88,
                "model_id": 276,
                "year_from": 1971,
                "year_to": 1986,
                "year_range": "04\/71-07\/86",
                "sub_model": "2.0 (D11, F11)",
                "chassis_code": "F,D",
                "engine_code": null,
                "cc": "1971",
                "power": "68",
                "body_type": "Wagon",
                "brake_system": "Hydraulic",
                "pivot": {
                    "product_id": 1,
                    "vehicle_id": 31828
                },
                "make": {
                    "id": 88,
                    "name": "PEUGEOT"
                },
                "model": {
                    "id": 276,
                    "make_id": 88,
                    "name": "504"
                }
            }
        ],
        "images": [],
        "categories": [
            {
                "id": 14,
                "parent_id": "1",
                "name": "Brake Pads",
                "description": "",
                "icon": null,
                "image": null,
                "pivot": {
                    "product_id": 1,
                    "category_id": 14
                }
            }
        ],
        "notes": []
    },
    "message": "Product details"
}
```

### HTTP Request
`GET api/product/{product_id}/detail`


<!-- END_8467efa5c5527721103d40adc52944d2 -->

<!-- START_8132b3a1b358d7251ae63f815cdccd92 -->
## Notes
Add notes to product.

> Example request:

```bash
curl -X POST "http://flexibledrive.localhost.com/api/product/note" \
    -H "Content-Type: application/json" \
    -d '{"product":"perferendis","description":"qui","user_name":"est"}'

```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/product/note");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "product": "perferendis",
    "description": "qui",
    "user_name": "est"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/product/note`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    product | Integer |  required  | Product ID ( Table : "products" )
    description | String |  required  | Note (description)
    user_name | String |  required  | User name

<!-- END_8132b3a1b358d7251ae63f815cdccd92 -->

#Profile 


<!-- START_a4251b7143964e3f7d9fb181a7b86ba5 -->
## User Profile
This API use to get Logged in user profile detail.

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/user/profile" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/user/profile");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "status": 0,
    "message": "Unauthorized!"
}
```

### HTTP Request
`GET api/user/profile`


<!-- END_a4251b7143964e3f7d9fb181a7b86ba5 -->

<!-- START_1e0c3bb3e26bb161b8fd6f43935f4a57 -->
## User Profile Update
This API use to Update Logged in user profile detail.

> Example request:

```bash
curl -X POST "http://flexibledrive.localhost.com/api/user/profile" \
    -H "Content-Type: application/json" \
    -d '{"first_name":"john","last_name":"doe","company_name":"test company","address_line1":"test adress1","address_line2":"test adress2","state":"ACT","zip":"123456","profile_image":"base64 string"}'

```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/user/profile");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "first_name": "john",
    "last_name": "doe",
    "company_name": "test company",
    "address_line1": "test adress1",
    "address_line2": "test adress2",
    "state": "ACT",
    "zip": "123456",
    "profile_image": "base64 string"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "id": 11,
        "first_name": "Krutik1",
        "last_name": "Patel1",
        "email": "kkrutikk@gmail.com",
        "email_verified_at": null,
        "account_code": null,
        "company_name": "Test Company",
        "address_line1": "New Test Street",
        "address_line2": "Test City",
        "state": "Test State",
        "zip": "1224",
        "profile_image": "BbU16LP5Uc.jpg",
        "mobile": "1234567890",
        "created_at": "2019-09-30 12:21:15",
        "updated_at": "2019-10-04 09:27:45",
        "image_url": "http:\/\/35.164.124.177\/fd-backend\/public\/storage\/users\/BbU16LP5Uc.jpg"
    },
    "message": "User Profile"
}
```

### HTTP Request
`POST api/user/profile`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    first_name | String |  required  | First Name
    last_name | String |  required  | Last Name
    company_name | String |  required  | Company 
    address_line1 | String |  required  | Address Line1
    address_line2 | String |  required  | Address Line2
    state | String |  required  | ACT, NSW, NT, QLD, SA, TAS, VIC, WA
    zip | String |  required  | Pincode (Zipcode) 
    profile_image | String |  required  | User Profile image base64 incoded  

<!-- END_1e0c3bb3e26bb161b8fd6f43935f4a57 -->

<!-- START_a28b7125aa705af2978873aa05a39708 -->
## User password update
This API will update logged in user password.

> Example request:

```bash
curl -X POST "http://flexibledrive.localhost.com/api/user/password/change" \
    -H "Content-Type: application/json" \
    -d '{"old_password":"johnDeo@123","confirm_password":"johnDeo@123"}'

```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/user/password/change");

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
}

let body = {
    "old_password": "johnDeo@123",
    "confirm_password": "johnDeo@123"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/user/password/change`

#### Body Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    old_password | String |  required  | password String required password having atlist 1 uppercase, 1 lowercase , 1 number, 1 symbol and minimum 8 character long. 
    confirm_password | String |  required  | Confirm Password

<!-- END_a28b7125aa705af2978873aa05a39708 -->

#Search Filters


<!-- START_5231058cc9a74e89de3124e4de1ed93a -->
## Product Count
This API will return count of product.

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/products/count?make_id=99&model_id=353&sub_model=2.0+i&year=1985&chassis_code=69%2CAF3%2C78&engine_code=B201&cc=1985&power=81&body_type=Hatchback&rego_number=99&state=SA&vin_number=JF1BL5KS57G03135" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/products/count");

    let params = {
            "make_id": "99",
            "model_id": "353",
            "sub_model": "2.0 i",
            "year": "1985",
            "chassis_code": "69,AF3,78",
            "engine_code": "B201",
            "cc": "1985",
            "power": "81",
            "body_type": "Hatchback",
            "rego_number": "99",
            "state": "SA",
            "vin_number": "JF1BL5KS57G03135",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "count": 0
    },
    "message": "Products Count"
}
```

### HTTP Request
`GET api/products/count`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    make_id |  optional  | (Integer) Make ID ( Table : "makes" )
    model_id |  optional  | (Integer) Model ID ( Table : "models" )
    sub_model |  optional  | (String) car sub model
    year |  optional  | (Integer) Year
    chassis_code |  optional  | (String) car chassis code
    engine_code |  optional  | (String) car engine code  
    cc |  optional  | (String) car engine cc 
    power |  optional  | (String) car engine power 
    body_type |  optional  | (String) car body type
    rego_number |  optional  | (String) vehicle Rego Number
    state |  optional  | String (ACT, NSW, NT, QLD, SA, TAS, VIC, WA)
    vin_number |  optional  | (String) VIN Number

<!-- END_5231058cc9a74e89de3124e4de1ed93a -->

<!-- START_bac7ff905df2f98455334f0f92814f66 -->
## Search Form Dropdowns
This API will return Dropdown values.

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/products/search/dropdowns?make_id=99&model_id=353&sub_model=2.0+i&year=1985&chassis_code=69%2CAF3%2C78&engine_code=B201&cc=1985&power=81&body_type=Hatchback" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/products/search/dropdowns");

    let params = {
            "make_id": "99",
            "model_id": "353",
            "sub_model": "2.0 i",
            "year": "1985",
            "chassis_code": "69,AF3,78",
            "engine_code": "B201",
            "cc": "1985",
            "power": "81",
            "body_type": "Hatchback",
        };
    Object.keys(params).forEach(key => url.searchParams.append(key, params[key]));

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": {
        "years": [
            1934,
            1935,
            1936,
            1937,
            1938,
            1939,
            1940,
            1941,
            1942,
            1943,
            1944,
            1945,
            1946,
            1947,
            1948,
            1949,
            1950,
            1951,
            1952,
            1953,
            1954,
            1955,
            1956,
            1957,
            1958,
            1959,
            1960,
            1961,
            1962,
            1963,
            1964,
            1965,
            1966,
            1967,
            1968,
            1969,
            1970,
            1971,
            1972,
            1973,
            1974,
            1975,
            1976,
            1977,
            1978,
            1979,
            1980,
            1981,
            1982,
            1983,
            1984,
            1985,
            1986,
            1987,
            1988,
            1989,
            1990,
            1991,
            1992,
            1993,
            1994,
            1995,
            1996,
            1997,
            1998,
            1999,
            2000,
            2001,
            2002,
            2003,
            2004,
            2005,
            2006,
            2007,
            2008,
            2009,
            2010,
            2011,
            2012,
            2013,
            2014,
            2015,
            2016,
            2017,
            2018,
            2019
        ],
        "sub_models": [],
        "chassis_code": [],
        "engine_code": [],
        "body_type": []
    },
    "message": "Search Results"
}
```

### HTTP Request
`GET api/products/search/dropdowns`

#### Query Parameters

Parameter | Status | Description
--------- | ------- | ------- | -----------
    make_id |  required  | (Integer) Make ID ( Table : "makes" )
    model_id |  required  | (Integer) Model ID ( Table : "models" )
    sub_model |  optional  | (String) car sub model
    year |  optional  | (Integer) Year
    chassis_code |  optional  | (String) car chassis code
    engine_code |  optional  | (String) car engine code  
    cc |  optional  | (String) car engine cc 
    power |  optional  | (String) car engine power 
    body_type |  optional  | (String) car body type

<!-- END_bac7ff905df2f98455334f0f92814f66 -->

<!-- START_9b68578711f924e2bfa7f45fe13075d3 -->
## Search Form Makes
This API will return All Makes (Car Company).

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/makes" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/makes");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": [
        {
            "id": 3854,
            "name": "ABARTH"
        },
        {
            "id": 609,
            "name": "AC"
        },
        {
            "id": 1505,
            "name": "ACURA"
        },
        {
            "id": 1480,
            "name": "AIXAM"
        },
        {
            "id": 2,
            "name": "ALFA ROMEO"
        },
        {
            "id": 866,
            "name": "ALPINA"
        },
        {
            "id": 810,
            "name": "ALPINE"
        },
        {
            "id": 4730,
            "name": "ALVIS"
        },
        {
            "id": 2246,
            "name": "AMC"
        },
        {
            "id": 2526,
            "name": "ARMSTRONG SIDDELEY"
        },
        {
            "id": 879,
            "name": "ASIA MOTORS"
        },
        {
            "id": 881,
            "name": "ASTON MARTIN"
        },
        {
            "id": 5,
            "name": "AUDI"
        },
        {
            "id": 6,
            "name": "AUSTIN"
        },
        {
            "id": 1538,
            "name": "AUSTIN-HEALEY"
        },
        {
            "id": 10,
            "name": "BEDFORD"
        },
        {
            "id": 815,
            "name": "BENTLEY"
        },
        {
            "id": 1485,
            "name": "BERTONE"
        },
        {
            "id": 99000013,
            "name": "BMC"
        },
        {
            "id": 16,
            "name": "BMW"
        },
        {
            "id": 5173,
            "name": "BOLWELL"
        },
        {
            "id": 136,
            "name": "BORGWARD"
        },
        {
            "id": 1487,
            "name": "BRISTOL"
        },
        {
            "id": 788,
            "name": "BUGATTI"
        },
        {
            "id": 816,
            "name": "BUICK"
        },
        {
            "id": 819,
            "name": "CADILLAC"
        },
        {
            "id": 1489,
            "name": "CARBODIES"
        },
        {
            "id": 1490,
            "name": "CATERHAM"
        },
        {
            "id": 2887,
            "name": "CHERY"
        },
        {
            "id": 138,
            "name": "CHEVROLET"
        },
        {
            "id": 20,
            "name": "CHRYSLER"
        },
        {
            "id": 99000003,
            "name": "CHRYSLER AUSTRALIA"
        },
        {
            "id": 21,
            "name": "CITROEN"
        },
        {
            "id": 99000002,
            "name": "COMMER"
        },
        {
            "id": 139,
            "name": "DACIA"
        },
        {
            "id": 185,
            "name": "DAEWOO"
        },
        {
            "id": 24,
            "name": "DAF"
        },
        {
            "id": 1024030001,
            "name": "DAF"
        },
        {
            "id": 25,
            "name": "DAIHATSU"
        },
        {
            "id": 26,
            "name": "DAIMLER"
        },
        {
            "id": 1494,
            "name": "DE LOREAN"
        },
        {
            "id": 1495,
            "name": "DE TOMASO"
        },
        {
            "id": 99000007,
            "name": "DMC"
        },
        {
            "id": 29,
            "name": "DODGE"
        },
        {
            "id": 4468,
            "name": "DS"
        },
        {
            "id": 824,
            "name": "EAGLE"
        },
        {
            "id": 143,
            "name": "ERF"
        },
        {
            "id": 2857,
            "name": "EUNOS"
        },
        {
            "id": 700,
            "name": "FERRARI"
        },
        {
            "id": 35,
            "name": "FIAT"
        },
        {
            "id": 3738,
            "name": "FISKER"
        },
        {
            "id": 36,
            "name": "FORD"
        },
        {
            "id": 2864,
            "name": "FORD ASIA \/ OCEANIA"
        },
        {
            "id": 1496,
            "name": "FORD AUSTRALIA"
        },
        {
            "id": 99000006,
            "name": "FORD GERMANY"
        },
        {
            "id": 99000008,
            "name": "FORD UK"
        },
        {
            "id": 776,
            "name": "FORD USA"
        },
        {
            "id": 2867,
            "name": "FOTON"
        },
        {
            "id": 3297,
            "name": "FPV"
        },
        {
            "id": 147,
            "name": "FREIGHTLINER"
        },
        {
            "id": 101560002,
            "name": "FUSO"
        },
        {
            "id": 3741,
            "name": "FUSO (MITSUBISHI)"
        },
        {
            "id": 148,
            "name": "GAZ"
        },
        {
            "id": 2590,
            "name": "GEELY"
        },
        {
            "id": 4473,
            "name": "GENESIS"
        },
        {
            "id": 1498,
            "name": "GINETTA"
        },
        {
            "id": 39,
            "name": "GMC"
        },
        {
            "id": 2903,
            "name": "GREAT WALL"
        },
        {
            "id": 3968,
            "name": "HAVAL"
        },
        {
            "id": 150,
            "name": "HEULIEZ"
        },
        {
            "id": 2794,
            "name": "HILLMAN"
        },
        {
            "id": 151,
            "name": "HINO"
        },
        {
            "id": 99950001,
            "name": "HINO"
        },
        {
            "id": 101560001,
            "name": "Hino"
        },
        {
            "id": 801,
            "name": "HOLDEN"
        },
        {
            "id": 45,
            "name": "HONDA"
        },
        {
            "id": 3276,
            "name": "HSV"
        },
        {
            "id": 4729,
            "name": "HUMBER"
        },
        {
            "id": 99000011,
            "name": "HUMBER"
        },
        {
            "id": 1506,
            "name": "HUMMER"
        },
        {
            "id": 183,
            "name": "HYUNDAI"
        },
        {
            "id": 3123,
            "name": "HYUNDAI (BEIJING)"
        },
        {
            "id": 1526,
            "name": "INFINITI"
        },
        {
            "id": 52,
            "name": "INNOCENTI"
        },
        {
            "id": 2460,
            "name": "INTERNATIONAL"
        },
        {
            "id": 2115,
            "name": "IRISBUS"
        },
        {
            "id": 54,
            "name": "ISUZU"
        },
        {
            "id": 55,
            "name": "IVECO"
        },
        {
            "id": 56,
            "name": "JAGUAR"
        },
        {
            "id": 882,
            "name": "JEEP"
        },
        {
            "id": 1511,
            "name": "JENSEN"
        },
        {
            "id": 3040,
            "name": "JMC"
        },
        {
            "id": 155,
            "name": "KENWORTH"
        },
        {
            "id": 184,
            "name": "KIA"
        },
        {
            "id": 2760,
            "name": "KTM"
        },
        {
            "id": 63,
            "name": "LADA"
        },
        {
            "id": 701,
            "name": "LAMBORGHINI"
        },
        {
            "id": 64,
            "name": "LANCIA"
        },
        {
            "id": 1820,
            "name": "LAND ROVER"
        },
        {
            "id": 2589,
            "name": "LANDWIND (JMC)"
        },
        {
            "id": 1380,
            "name": "LDV"
        },
        {
            "id": 842,
            "name": "LEXUS"
        },
        {
            "id": 2407,
            "name": "LEYLAND-INNOCENTI"
        },
        {
            "id": 1200,
            "name": "LINCOLN"
        },
        {
            "id": 802,
            "name": "LOTUS"
        },
        {
            "id": 3047,
            "name": "LTI"
        },
        {
            "id": 1024030003,
            "name": "MACK"
        },
        {
            "id": 67,
            "name": "MACK"
        },
        {
            "id": 1280,
            "name": "MAHINDRA"
        },
        {
            "id": 1024030006,
            "name": "MAN"
        },
        {
            "id": 69,
            "name": "MAN"
        },
        {
            "id": 1516,
            "name": "MARCOS"
        },
        {
            "id": 771,
            "name": "MASERATI"
        },
        {
            "id": 3300,
            "name": "MAXUS"
        },
        {
            "id": 2164,
            "name": "MAYBACH"
        },
        {
            "id": 72,
            "name": "MAZDA"
        },
        {
            "id": 99000014,
            "name": "MAZDASPEED"
        },
        {
            "id": 1518,
            "name": "MCLAREN"
        },
        {
            "id": 74,
            "name": "MERCEDES-BENZ"
        },
        {
            "id": 99860001,
            "name": "MERCHANDISE"
        },
        {
            "id": 161,
            "name": "MERCURY"
        },
        {
            "id": 75,
            "name": "MG"
        },
        {
            "id": 1522,
            "name": "MINELLI"
        },
        {
            "id": 1523,
            "name": "MINI"
        },
        {
            "id": 77,
            "name": "MITSUBISHI"
        },
        {
            "id": 2904,
            "name": "MITSUOKA"
        },
        {
            "id": 803,
            "name": "MORGAN"
        },
        {
            "id": 78,
            "name": "MORRIS"
        },
        {
            "id": 813,
            "name": "MOSKVICH"
        },
        {
            "id": 79,
            "name": "MULTICAR"
        },
        {
            "id": 162,
            "name": "NEOPLAN"
        },
        {
            "id": 80,
            "name": "NISSAN"
        },
        {
            "id": 3141,
            "name": "NISSAN (DFAC)"
        },
        {
            "id": 3625,
            "name": "NOBLE"
        },
        {
            "id": 81,
            "name": "NSU"
        },
        {
            "id": 1141,
            "name": "OLDSMOBILE"
        },
        {
            "id": 84,
            "name": "OPEL"
        },
        {
            "id": 1533,
            "name": "PERODUA"
        },
        {
            "id": 88,
            "name": "PEUGEOT"
        },
        {
            "id": 181,
            "name": "PIAGGIO"
        },
        {
            "id": 850,
            "name": "PLYMOUTH"
        },
        {
            "id": 774,
            "name": "PONTIAC"
        },
        {
            "id": 92,
            "name": "PORSCHE"
        },
        {
            "id": 778,
            "name": "PROTON"
        },
        {
            "id": 3689,
            "name": "RAM"
        },
        {
            "id": 773,
            "name": "RELIANT"
        },
        {
            "id": 93,
            "name": "RENAULT"
        },
        {
            "id": 694,
            "name": "RENAULT TRUCKS"
        },
        {
            "id": 1539,
            "name": "RILEY"
        },
        {
            "id": 705,
            "name": "ROLLS-ROYCE"
        },
        {
            "id": 95,
            "name": "ROVER"
        },
        {
            "id": 99,
            "name": "SAAB"
        },
        {
            "id": 1024030005,
            "name": "SCANIA"
        },
        {
            "id": 103,
            "name": "SCANIA"
        },
        {
            "id": 2714,
            "name": "SCION"
        },
        {
            "id": 104,
            "name": "SEAT"
        },
        {
            "id": 2488,
            "name": "SETRA"
        },
        {
            "id": 1547,
            "name": "SHELBY"
        },
        {
            "id": 4731,
            "name": "SINGER"
        },
        {
            "id": 99000010,
            "name": "SINGER"
        },
        {
            "id": 106,
            "name": "SKODA"
        },
        {
            "id": 1138,
            "name": "SMART"
        },
        {
            "id": 2931,
            "name": "SOLARIS"
        },
        {
            "id": 2755,
            "name": "SPYKER"
        },
        {
            "id": 175,
            "name": "SSANGYONG"
        },
        {
            "id": 1550,
            "name": "STANDARD"
        },
        {
            "id": 107,
            "name": "SUBARU"
        },
        {
            "id": 2716,
            "name": "SUNBEAM"
        },
        {
            "id": 109,
            "name": "SUZUKI"
        },
        {
            "id": 110,
            "name": "TALBOT"
        },
        {
            "id": 178,
            "name": "TATA"
        },
        {
            "id": 3443,
            "name": "TAZZARI"
        },
        {
            "id": 3328,
            "name": "TESLA"
        },
        {
            "id": 102410001,
            "name": "Test Make"
        },
        {
            "id": 3514,
            "name": "THINK"
        },
        {
            "id": 111,
            "name": "TOYOTA"
        },
        {
            "id": 112,
            "name": "TRIUMPH"
        },
        {
            "id": 861,
            "name": "TVR"
        },
        {
            "id": 5206,
            "name": "UD NISSAN DIESEL"
        },
        {
            "id": 1023780001,
            "name": "UNIVERSAL PRODUCTS"
        },
        {
            "id": 117,
            "name": "VAUXHALL"
        },
        {
            "id": 1024030004,
            "name": "VOLVO"
        },
        {
            "id": 120,
            "name": "VOLVO"
        },
        {
            "id": 121,
            "name": "VW"
        },
        {
            "id": 907,
            "name": "WESTFIELD"
        },
        {
            "id": 1558,
            "name": "WIESMANN"
        },
        {
            "id": 1541,
            "name": "WOLSELEY"
        },
        {
            "id": 124,
            "name": "ZASTAVA"
        },
        {
            "id": 1139,
            "name": "ZAZ"
        },
        {
            "id": 1111,
            "name": "ZETOR"
        },
        {
            "id": 2911,
            "name": "ZHONGXING (AUTO)"
        }
    ],
    "message": "Makes"
}
```

### HTTP Request
`GET api/makes`


<!-- END_9b68578711f924e2bfa7f45fe13075d3 -->

<!-- START_cbfa775b8e49ab0e787640d1a81d58da -->
## Search Form Models
This API will return All Models of particuler make(company) makes/{2}/models.

> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/makes/1/models" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/makes/1/models");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (200):

```json
{
    "success": true,
    "data": [],
    "message": "Models"
}
```

### HTTP Request
`GET api/makes/{make_id}/models`


<!-- END_cbfa775b8e49ab0e787640d1a81d58da -->

#general


<!-- START_dd9efe913cb5cc2816593f63a8f11c44 -->
## api/soap
> Example request:

```bash
curl -X GET -G "http://flexibledrive.localhost.com/api/soap" 
```

```javascript
const url = new URL("http://flexibledrive.localhost.com/api/soap");

let headers = {
    "Accept": "application/json",
    "Content-Type": "application/json",
}

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (500):

```json
{
    "message": "Server Error"
}
```

### HTTP Request
`GET api/soap`


<!-- END_dd9efe913cb5cc2816593f63a8f11c44 -->


