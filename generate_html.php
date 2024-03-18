<?php

// Define the list of views
$views = [
    "welcome.blade.php",
    "login.blade.php",
    "register.blade.php",
    "forgot-password.blade.php",
    "reset-password.blade.php",
    "dashboard.blade.php",
    "profile.blade.php",
    "profile/edit.blade.php",
    "account-settings.blade.php",
    "change-password.blade.php",
    "verify-email.blade.php",
    "errors/404.blade.php",
    "confirm.blade.php",
    "notifications.blade.php",
    "layouts/app.blade.php",
    "partials/header.blade.php",
    "partials/footer.blade.php",
    "facilities/index.blade.php",
    "facilities/create.blade.php",
    "facilities/show.blade.php",
    "facilities/edit.blade.php",
    "products/index.blade.php",
    "products/create.blade.php",
    "products/show.blade.php",
    "products/edit.blade.php",
    "services/index.blade.php",
    "services/create.blade.php",
    "services/show.blade.php",
    "services/edit.blade.php",
    "orders/index.blade.php",
    "orders/show.blade.php",
    "orders/edit.blade.php",
    "orders/create.blade.php",
    "checkout/index.blade.php",
    "checkout/success.blade.php",
    "admin/dashboard.blade.php",
    "admin/users/index.blade.php",
    "admin/users/create.blade.php",
    "admin/users/show.blade.php",
    "admin/users/edit.blade.php",
    "reservations/index.blade.php",
    "reservations/create.blade.php",
    "reservations/show.blade.php",
    "reservations/edit.blade.php",
];

// for each view, create a file in the "frontend-prototypes" directory. 
// when you encounter a /, create a directory instead of a file out of the string before the /, like "errors/404.blade.php" should create a directory "errors" and a file "404.blade.php" inside of it.
// but don't make a php file, just an html file.


foreach ($views as $view) {
    $path = "frontend-prototypes";
    $directories = explode("/", $view);
    $filename = array_pop($directories);
    foreach ($directories as $directory) {
        $path .= "/$directory";
        if (!file_exists($path)) {
            mkdir($path);
        }
    }
    $path .= "/$filename";
    $path = str_replace(".blade.php", ".html", $path);
    file_put_contents($path, "<!-- $view -->");
}
// The above code will create a directory structure that looks like this:

// frontend-prototypes/
// ├── account-settings.blade.php
// ├── admin
// │   ├── dashboard.blade.php
// │   └── users
// │       ├── create.blade.php
// │       ├── edit.blade.php
// │       ├── index.blade.php
// │       └── show.blade.php

// and so on.
