<?php

require '../includes/config.php';


function getCustomerList()
{
    global $connect;
    $query = "select * from customers";
    $query_run = mysqli_query($connect, $query);

    if ($query_run) {
        if (mysqli_num_rows($query_run) > 0) {
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
            $data = [
                "status" => 200,
                "message" => 'Customer List Featched Successfully',
                "data" => $res,

            ];
            header("HTTP/1.0 200 Successfully");
            return json_encode($data);
        } else {
            $data = [
                "status" => 500,
                "message" => 'No Customer Found',
            ];
            header("HTTP/1.0 500 No Customer Found");
            return json_encode($data);
        }
    } else {
        $data = [
            "status" => 500,
            "message" => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}

function getCustomer($customerParams)
{

    global $connect;

    if ($customerParams["id"] == null) {
        return error442("Enter your customer id");
    }

    $customerId = mysqli_real_escape_string($connect, $customerParams['id']);

    $query = "select * from customers where id='$customerId' LIMIT 1";
    $result = mysqli_query($connect, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $res = mysqli_fetch_assoc($result);
            $data = [
                "status" => 200,
                "message" => 'Customer Fetched Successfully',
                "data" => $res
            ];
            header("HTTP/1.0 200 Sucess");
            return json_encode($data);
        } else {
            $data = [
                "status" => 404,
                "message" => 'No Customer Found',
            ];
            header("HTTP/1.0 404 Not Found");
            return json_encode($data);
        }
    } else {
        $data = [
            "status" => 500,
            "message" => 'Internal Server Error',
        ];
        header("HTTP/1.0 500 Internal Server Error");
        return json_encode($data);
    }
}

function error442($message)
{
    $data = [
        "status" => 422,
        "message" => $message,
    ];
    header("HTTP/1.0 422 Umprocessable Entity");
    echo json_encode($data);
    exit();
}
function storeCustomer($customerInput)
{

    global $connect;
    $name = mysqli_real_escape_string($connect, $customerInput["name"]);
    $email = mysqli_real_escape_string($connect, $customerInput["email"]);
    $phone = mysqli_real_escape_string($connect, $customerInput["phone"]);

    if (empty(trim($name))) {
        return error442("Enter your name");
    } elseif (empty(trim($email))) {
        return error442("Enter your email");
    } elseif (empty(trim($phone))) {
        return error442("Enter your phone");
    } else {
        $query = "INSERT INTO customers(name,email,phone) VALUES ('$name','$email','$phone')";
        $result = mysqli_query($connect, $query);

        if ($result) {
            $data = [
                "status" => 201,
                "message" => 'Customers Inserted Successfully',
            ];
            header("HTTP/1.0 201 Inserted");
            return json_encode($data);
        } else {
            $data = [
                "status" => 500,
                "message" => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }
}

function updateCustomer($customerInput, $customParams)
{
    global $connect;

    if (!isset($customParams["id"])) {
        return error442("Customer id not found in URL");
    } else if($customParams["id"] == null){
        return error442("Enter the Customer id ");
    }

    $customerId = mysqli_real_escape_string($connect, $customParams["id"]);
    $name = mysqli_real_escape_string($connect, $customerInput["name"]);
    $email = mysqli_real_escape_string($connect, $customerInput["email"]);
    $phone = mysqli_real_escape_string($connect, $customerInput["phone"]);

    if (empty(trim($name))) {
        return error442("Enter your name");
    } elseif (empty(trim($email))) {
        return error442("Enter your email");
    } elseif (empty(trim($phone))) {
        return error442("Enter your phone");
    } else {
        $query = "UPDATE customers SET name ='$name', email ='$email' , phone = '$phone' where id='$customerId' LIMIT 1";
        $result = mysqli_query($connect, $query);

        if ($result) {
            $data = [
                "status" => 200,
                "message" => 'Customers Updated Successfully',
            ];
            header("HTTP/1.0 200 Update");
            return json_encode($data);
        } else {
            $data = [
                "status" => 500,
                "message" => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
        }
    }

}

function deleteCustomer($customerParams){
    global $connect;

    if (!isset($customerParams["id"])) {
        return error442("Customer id not found in URL");
    } else if($customerParams["id"] == null){
        return error442("Enter the Customer id ");
    }

    $customerId = mysqli_real_escape_string($connect, $customerParams["id"]);
    $query = "DELETE from customers where id='$customerId'";
    $result = mysqli_query($connect, $query);

    if ($result) {
        $data = [
            "status" => 204,
            "message" => 'Customer Delete Successfully',
        ];
        header("HTTP/1.0 204 Deleted");
        return json_encode($data);
    } else {
        $data = [
            "status" => 404,
            "message" => 'Customer Not Found',
        ];
        header("HTTP/1.0 404 Not Found");
        return json_encode($data);
    }
    

}


?>