# PHP SmailPro
Mã nguồn để làm api get mail, otp từ server.
# Cách sử dụng:
### 1. Tạo token (GET/POST)
```
http://localhost/?type=create-mail&mail_type=other&username=000000&domain=storegmail.com
```
Params
```
type: create-mail (*)
mail_type: other/gmail/googlemail (*)
username: random hoặc có thể tùy theo tên (tối thiểu 6 kí tự username, chỉ áp dụng đối với mail other)
domain: random hoặc có thể tùy chỉnh (chỉ áp dụng đối với mail other)
```

Response:
```
{"statusCode":200,"email":"000000@storegmail.com","type":"other","token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.IntcImRhdGFcIjp7XCJlbWFpbFwiOlwiMDAwMDAwQHN0b3JlZ21haWwuY29tXCIsXCJ0aW1lc3RhbXBcIjoxNjU5NDI5NjYwfSxcImNyZWF0ZWRfYXRcIjoxNjU5NTE2MDY3fSI.XfpHRBNDSs5zn1HXrx_ZkVey6TVB6WVmIfbe8-CEOR8","message":"OK"}
```
