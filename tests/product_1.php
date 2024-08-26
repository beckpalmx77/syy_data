<?php

// Optional, Define the price prefix, can be pulled from database or hardcoded
$price_prefix = 'R';

$products = array(

    array(
        'id' => '1',
        'title' => 'Product 1',
        'sku' => 'code-2',
        'price' => '95.00',
        'category' => 'Shirts',
        'image' => 'https://via.placeholder.com/500x300.png'

    ),

    array(
        'id' => '2',
        'title' => 'Product 2',
        'sku' => 'code-2',
        'price' => '145.00',
        'category' => 'Pants',
        'image' => 'https://via.placeholder.com/500x300.png'
    ),

    array(
        'id' => '3',
        'title' => 'Product 3',
        'sku' => 'code-3',
        'price' => '895.00',
        'category' => 'Shirts',
        'image' => 'https://via.placeholder.com/500x300.png'

    ),

    array(

        'id' => '4',
        'title' => 'Product 4',
        'sku' => 'code-4',
        'price' => '295.00',
        'category' => 'Pants',
        'image' => 'https://via.placeholder.com/500x300.png'
    ),

    array(

        'id' => '5',
        'title' => 'Product 5',
        'sku' => 'code-5',
        'price' => '215.00',
        'category' => 'Caps',
        'image' => 'https://via.placeholder.com/500x300.png'

    ),

    array(

        'id' => '6',
        'title' => 'Product 6',
        'sku' => 'code-6',
        'price' => '365.00',
        'category' => 'Shirts',
        'image' => 'https://via.placeholder.com/500x300.png'

    ),

    array(

        'id' => '7',
        'title' => 'Product 7',
        'sku' => 'code-7',
        'price' => '95.00',
        'category' => 'Caps',
        'image' => 'https://via.placeholder.com/500x300.png'

    ),

    array(

        'id' => '8',
        'title' => 'Product 8',
        'sku' => 'code-8',
        'price' => '495.00',
        'category' => 'Caps',
        'image' => 'https://via.placeholder.com/500x300.png'

    ),

    array(
        'id' => '9',
        'title' => 'Product 9',
        'sku' => 'code-9',
        'price' => '95.00',
        'category' => 'Caps',
        'image' => 'https://via.placeholder.com/500x300.png'

    ),
);


// Declare $filtered_products array to store products in the filter $_GET params
$filtered_products = array();

// Declare $categories array to store categories
$categories = array();

// Loop through products and store categories into categories array. Also store filtered products into an array

foreach ($products as $product) {

    // Make sure item has a category key and a value, assigned as key and value to prevent duplicates
    if ($product['category'] && !empty($product['category'])) {
        $categories[$product['category']] = $product['category'];
    }

    //Store filtered products if set
    if (isset($_GET['category']) && !empty($_GET['category']) && $product['category'] == $_GET['category']) {
        $filtered_products[] = $product;
    }
}

// Get the total products based on filter must come AFTER the loop above
$total_products = isset($_GET['category']) && !empty($_GET['category']) ? count($filtered_products) : count($products);

// Declare a variable for our current page. If no page is set, the default is page 1
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Declare $limit value this can either be another filter option, a value pulled from the database (like user preferences) or a fixed value
$limit = 2;

// Declare an offset based on our current page (if we're not on page 1).
if (!empty($current_page) && $current_page > 1) {
    $offset = ($current_page * $limit) - $limit;
    /*
        for example, if we are on page 3 and we're only showing 2 items per page, we've already seen four items, two on page 1 and two on page 2. So our next number should be 5.
        We need to offset by 4.
        (3 * 2) - 2
        3 * 2 = 6, 6 - 2 = 4 (so we offset the value by 4 )
    */
} else {
    $offset = 0;
}

// Get the total pages rounded up the nearest whole number
$total_pages = ceil($total_products / $limit);


// Declare active category filter to use when we paginate so we don't lose the filter
$filtered_category_query = isset($_GET['category']) ? '&category=' . $_GET['category'] : '';

// When we filter, we want to know the range of products we're viewing
$first_product_displayed = $offset + 1;
//example : if we're on page 3, our offset is 4 ( limit(page 1) + limit(page2) ) so 4 + 1 = 5 (first product in view is 5)

// if the total products is more than the current offset x 2 + 2 then our last product is the offset + 2 or else it should be the total
$last_product_displayed = $total_products >= ($offset * $limit) + $limit ? $offset + $limit : $total_products;
// example 1 : if we're on page 3, our offset is 4 ( limit(page 1) + limit(page2) ) so 4 x 2 = 8, + 2 = 10 (last product in view is 10)
// example 2 : if we're on page 2, our offset is 2 ( limit(page 2) ) so 2 x 2 = 4, + 2 = 6 (last product in view is 6)

// Display the current range in view
if ($first_product_displayed === $last_product_displayed) {
    $range = 'the Last of ' . $total_products . ' Products';
} else {
    $range = $first_product_displayed . ' - ' . $last_product_displayed . ' of ' . $total_products . ' Products';
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Pagination Demo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <style>
        body {
            background-color: #eee;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .container {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            max-width: 800px;
            padding: 20px;
        }

        .filter {
            background-color: #eee;
            padding: 10px 20px;
            margin: 0 0 10px;
            text-align: center;
            border-radius: 5px;
            vertical-align: middle;
        }

        form {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        label,
        input,
        select {
            display: inline-block !important;
            margin-right: 10px;
            width: auto !important;
            margin-bottom: 0;
        }

        .product {
            padding: 20px;
        }

        .column-inner {
            border: 1px solid #ddd;
        }

        .img-fluid {
            width: 100%;
            height: auto;

        }

        .pagination {
            border-top: 1px solid #ddd;
            padding-top: 10px;
            margin-top: 20px;
            width: 100%;
            text-align: center;
            justify-content: center;
        }

        .product-title,
        .product-price,
        .product-cat {
            text-align: center;
        }

        .product-title {
            padding-top: 10px;
            font-size: 24px;
            color: #454545;

        }

        .product-price {
            font-size: 20px;
            color: #0062cc;
        }

        .product-cat {
            font-size: 16px;
            color: #767676;
        }
    </style>
</head>

<body>
<div class="container">

    <div class="filter row">

        <form class="form" action="" method="GET">
            <label>Filter Products</label>
            <select name="category" class="form-control form-control-sm">
                <option value=""></option>

                <?php

                //Sort categories alphabetically by value
                asort($categories);

                // List categories available to filter by
                foreach ($categories as $category) {

                    // check if we are in a filter already ('name' from <select>) to make the filter the selected option
                    if (isset($_GET['category']) && $_GET['category'] == $category) {
                        $selected = ' selected="selected" '; // or just selected will do as well
                    } else {
                        $selected = '';
                    }

                    // Add the category option in the <select>

                    echo '<option value="' . $category . '" ' . $selected . '>' . $category . '</option>';
                } // End categories for each


                ?>
            </select>
            <input type="submit" class="btn btn-primary btn-sm" />

            <span>Showing <?php


                echo $range;

                if (isset($_GET['category']) && !empty($_GET['category'])) {
                    echo ' in ' . $_GET['category'];
                }
                ?>
                </span>

        </form>

    </div> <!-- Filter -->

    <div class="products row">

        <?php

        // Redefine $products array if there are filters set
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            // Array Slice allows us to offset and limit array output
            $products = array_slice($filtered_products, $offset, $limit);
        }

        // or leave as is
        else {
            $products = array_slice($products, $offset, $limit);
        }

        // Loop through $products array

        foreach ($products as $product) { ?>

            <div class="col-md-6 product">
                <div class="column-inner">
                    <img src="<?php echo $product['image']; ?>" class="img-fluid" />
                    <h2 class="product-title"><?php echo $product['title']; ?></h2>
                    <h3 class="product-price"><?php echo $price_prefix     . $product['price']; ?></h3>
                    <p class="product-cat">Category: <?php echo $product['category']; ?></p>

                </div>
            </div>

        <?php } // End Products foreach


        ?>

    </div><!-- Products -->

    <?php

    if ($total_pages > 1) { ?>

        <nav aria-label="Page navigation">
            <ul class="pagination">

                <?php
                // When we're not on the first page, we'll have a paginator back to the beginning
                if ($current_page > 1) { ?>

                    <li class="page-item"><a class="page-link" href="<?php echo '?page=1' . $filtered_category_query; ?>">First</a></li>

                    <?php
                }

                // Loop through page numbers
                for ($page_in_loop = 1; $page_in_loop <= $total_pages; $page_in_loop++) {
                    // if the total pages is more than 2, we can limit the pagination. We'll also give the current page some classes to disable and style it in css
                    // if the page in the loop is more between

                    if ($total_pages > 3) {
                        if (($page_in_loop >= $current_page - 5 && $page_in_loop <= $current_page)  || ($page_in_loop <= $current_page + 5 && $page_in_loop >= $current_page)) {  ?>

                            <li class="page-item <?php echo $page_in_loop == $current_page ? 'active disabled' : ''; ?>">
                                <a class="page-link" href="<?php echo '?page=' . $page_in_loop . $filtered_category_query; ?> "><?php echo $page_in_loop; ?></a>
                            </li>

                        <?php }
                    }
                    // if the total pages doesn't look ugly, we can display all of them
                    else { ?>

                        <li class="page-item <?php echo $page_in_loop == $current_page ? 'active disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo '?page=' . $page_in_loop . $filtered_category_query; ?> "><?php echo $page_in_loop; ?></a>
                        </li>

                    <?php } // End if
                    ?>

                <?php } // end for loop

                // and the last page
                if ($current_page < $total_pages) { ?>

                    <li class="page-item"><a class="page-link" href="<?php echo '?page=' . $total_pages . $filtered_category_query; ?>">Last</a></li>

                <?php } ?>
            </ul>
        </nav>

    <?php } // End if total pages more than 1
    ?>

</div> <!-- Container -->
</body>

</html>
