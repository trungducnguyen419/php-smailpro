# PHP SmailPro
Mã nguồn để làm api get mail, otp từ server.
# Cách sử dụng:
### 1. Lấy danh sách domain other (GET)
```
http://localhost/?type=list-domain-other
```
Params
```
type: list-domain-other (*)
```

Response OK:
```JSON
{
  "statusCode": 200,
  "list_domain": [
    "donymails.com",
    "stempmail.com",
    "storegmail.com",
    "instasmail.com",
    "yousmail.com",
    "woopros.com"
  ],
  "message": "OK"
}
```

### 2. Tạo token (GET/POST)
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

Response OK:
```
{"statusCode":200,"email":"000000@storegmail.com","type":"other","token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.IntcImRhdGFcIjp7XCJlbWFpbFwiOlwiMDAwMDAwQHN0b3JlZ21haWwuY29tXCIsXCJ0aW1lc3RhbXBcIjoxNjU5NDI5NjYwfSxcImNyZWF0ZWRfYXRcIjoxNjU5NTE2MDY3fSI.XfpHRBNDSs5zn1HXrx_ZkVey6TVB6WVmIfbe8-CEOR8","message":"OK"}
```

Response ERROR:
```JSON
{
  "statusCode": 400,
  "message": "message error"
}
```

### 3. Lấy token mới (GET/POST)
```
http://localhost/?type=get-new-token&email=000000@yousmail.com&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.IntcImRhdGFcIjp7XCJlbWFpbFwiOlwiMDAwMDAwQHlvdXNtYWlsLmNvbVwiLFwidGltZXN0YW1wXCI6MTY1OTQyMzEyMH0sXCJjcmVhdGVkX2F0XCI6MTY1OTUwOTQ5OX0i.pr5lSmHyyJx2RVbGaaGvzK_i_bTV622zBsgWpLxt-6U
```
Params
```
type: get-new-token (*)
email: email của token (*)
token: token cũ (*)
```

Response OK:
```
{"statusCode":200,"new_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.IntcImRhdGFcIjp7XCJlbWFpbFwiOlwiMDAwMDAwQHlvdXNtYWlsLmNvbVwiLFwidGltZXN0YW1wXCI6MTY1OTQzMjA2MH0sXCJjcmVhdGVkX2F0XCI6MTY1OTUxODQ3NX0i.Ya4r48h4GvCicxLXoXmkIfzVWESHT7N1G7FCTzsTNhs","message":"OK"}
```

Response ERROR:
```JSON
{
  "statusCode": 400,
  "message": "message error"
}
```

### 4. Lấy danh sách list mail inbox (GET/POST)
```
http://localhost/smailpro/?type=get-list-mail&email=000000@yousmail.com&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.IntcImRhdGFcIjp7XCJlbWFpbFwiOlwiMDAwMDAwQHlvdXNtYWlsLmNvbVwiLFwidGltZXN0YW1wXCI6MTY1OTQyMzU0MH0sXCJjcmVhdGVkX2F0XCI6MTY1OTUwOTk3MX0i.sjKVt_ck3TH8mUqzzOwqdCUrKyssHaIWmKY8jbjwImQ
```
Params
```
type: get-list-mail (*)
email: email của token (*)
token: token (*)
```

Response OK:
```JSON
{
  "statusCode": 200,
  "list_mail": [
    {
      "message_id": "1826274e7f5136e0",
      "textFrom": "\"Trung Đức Nguyễn\" ",
      "textDate": "2022-08-03 13:46:30",
      "textSubject": "tiêu đề",
      "textTo": "000000@yousmail.com",
      "textSnippet": null
    }
  ],
  "message": "OK"
}
```

Response ERROR:
```JSON
{
  "statusCode": 400,
  "message": "message error"
}
```
