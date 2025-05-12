<?php

class CartController extends Controller {
    public function addToCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_SESSION['readyOrder'])) {
                $_SESSION['readyOrder'] = [];
            }
            if (!isset($_SESSION['readyOrder']['cart'])) {
                $_SESSION['readyOrder']['cart'] = [];
            }

            $itemId = $_POST['itemId'];
            $quantity = (int)$_POST['quantity'];
            $name = $_POST['name'];
            $price = (float)$_POST['price'];

            $found = false;
            foreach ($_SESSION['readyOrder']['cart'] as &$item) {
                if ($item['itemId'] == $itemId) {
                    $item['quantity'] += $quantity;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $_SESSION['readyOrder']['cart'][] = [
                    'itemId' => $itemId,
                    'itemName' => $name,
                    'itemPrice' => $price,
                    'quantity' => $quantity
                ];
            }

            header('Location: /cnpm-final/InventoryController/customerMenuPage');
        }
    }



    public function removeItem() {
        $input = json_decode(file_get_contents("php://input"), true);
        $index = $input['index'];

        if (isset($_SESSION['readyOrder']['cart'][$index])) {
            unset($_SESSION['readyOrder']['cart'][$index]);
            $_SESSION['readyOrder']['cart'] = array_values($_SESSION['readyOrder']['cart']); // Reindex
        }

        $this->respondWithCart();
    }

    public function updateQuantity() {
        $input = json_decode(file_get_contents("php://input"), true);
        $index = $input['index'];
        $quantity = (int)$input['quantity'];

        if (isset($_SESSION['readyOrder']['cart'][$index]) && $quantity > 0) {
            $_SESSION['readyOrder']['cart'][$index]['quantity'] = $quantity;
        }

        $this->respondWithCart();
    }

    private function respondWithCart() {
        $cartItems = $_SESSION['readyOrder']['cart'] ?? [];
        $total = 0;

        foreach ($cartItems as $item) {
            $total += $item['itemPrice'] * $item['quantity'];
        }

        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'cartItems' => $cartItems,
            'totalPrice' => $total
        ]);
        exit;
    }

    public function setTable() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tableNumber'])) {
            $_SESSION['readyOrder']['tableNumber'] = $_POST['tableNumber'];
            header('Location: /cnpm-final/InventoryController/customerMenuPage');
            exit;
        }
    }
}
?>
