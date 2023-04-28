```
$ php8.1 start.php 
[2023-04-28T14:46:07.968945+01:00] http-expect.DEBUG: started... [] []
```

```
$ curl 127.0.0.1:8080/foo
Not expected

$ curl -X POST 127.0.0.1:8081/expect -H 'Content-Type: application/json' -d '{"request":{"method":"GET","path":"/foo"},"response":{"body":"I have been expecting you!\n"}}'
{
    "success": true
}

$ curl 127.0.0.1:8080/foo
I have been expecting you!

$ curl 127.0.0.1:8080/foobar
Not expected

$ curl -X POST 127.0.0.1:8080/foo
Not expected

$ curl -X DELETE 127.0.0.1:8081/expect
{
    "success": true
}

$ curl 127.0.0.1:8080/foo
Not expected
```


```
[2023-04-28T14:46:30.167672+01:00] http-expect.DEBUG: Got request on proxy server {"method":"GET","path":"/foo"} []
[2023-04-28T14:46:30.167727+01:00] http-expect.ERROR: Request not expected [] []
[2023-04-28T14:47:59.212506+01:00] http-expect.DEBUG: Got request on api server {"method":"POST","path":"/expect","body":"{\"request\":{\"method\":\"GET\",\"path\":\"/foo\"},\"response\":{\"body\":\"I have been expecting you!\\n\"}}"} []
[2023-04-28T14:47:59.212638+01:00] http-expect.DEBUG: Adding expectation {"expectation":{"path":"/foo"}} []
[2023-04-28T14:48:05.202936+01:00] http-expect.DEBUG: Got request on proxy server {"method":"GET","path":"/foo"} []
[2023-04-28T14:48:05.202996+01:00] http-expect.DEBUG: Returning expected response [] []
[2023-04-28T14:48:13.475549+01:00] http-expect.DEBUG: Got request on proxy server {"method":"GET","path":"/foobar"} []
[2023-04-28T14:48:13.475612+01:00] http-expect.ERROR: Request not expected [] []
[2023-04-28T14:48:17.979532+01:00] http-expect.DEBUG: Got request on proxy server {"method":"POST","path":"/foo"} []
[2023-04-28T14:48:17.979588+01:00] http-expect.ERROR: Request not expected [] []
[2023-04-28T14:48:41.019028+01:00] http-expect.DEBUG: Got request on api server {"method":"DELETE","path":"/expect","body":""} []
[2023-04-28T14:48:45.874954+01:00] http-expect.DEBUG: Got request on proxy server {"method":"GET","path":"/foo"} []
[2023-04-28T14:48:45.875009+01:00] http-expect.ERROR: Request not expected [] []
```

