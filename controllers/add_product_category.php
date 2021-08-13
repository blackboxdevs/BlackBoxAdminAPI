<?php

$user = Utils::get_user_from_session($response);

$post = $request->getBody();

$db = Utils::get_db_object();

$category_exists = $db->select_one(
    "SELECT category_name FROM product_categories WHERE category_name=?",
    "s",
    array($post['name'])
);

if(!empty($category_exists)) {
    $response->json(array(
        "success" => false,
        "message" => "Category name already exists"
    ));
}

$query = $db->query(
    "INSERT INTO product_categories (category_name, description, entry_date) VALUES (?, ?, UTC_TIMESTAMP())",
    "ss",
    array($post['name'], $post['description'])
);

if(empty($query)) {
    $response->json(array(
        "success" => true,
        "token" => Utils::renew_session_token($user)
    ));
}