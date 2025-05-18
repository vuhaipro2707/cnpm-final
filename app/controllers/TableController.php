<?php
    class TableController extends Controller {
        public function manageTableLayout() {
            $tableModel = $this->model('Table');
            $positions = $tableModel->getAllLayout(); // Trả về mảng các 'layoutPosition'
            $this->view('staff/manage_table_layout', ['table' => $positions]);
        }

        public function updateLayout()
        {
            $layoutData = $_POST['layoutData'] ?? '[]';
            $layout = json_decode($layoutData, true);

            if (!is_array($layout)) {
                http_response_code(400);
                echo "Dữ liệu không hợp lệ.";
                return;
            }

            $tableModel = $this->model('Table');
            $existingTables = $tableModel->getAllLayout();  // <-- gọi hàm của bạn

            $existingMap = [];
            foreach ($existingTables as $table) {
                $existingMap[$table['layoutPosition']] = $table;
            }

            $newLayoutPositions = array_column($layout, 'layoutPosition');
            $newLayoutSet = array_flip($newLayoutPositions);
            // $this->view('layout/debug', ['error' => $newLayoutPositions]);
            // return;

            $counter = 1;
            foreach ($newLayoutPositions as $pos) {
                $newTableNumber = 'T' . $counter;

                if (isset($existingMap[$pos])) {
                    $existing = $existingMap[$pos];
                    $status = $existing['status'];

                    if ($status === 'inactive') {
                        $tableModel->updateStatusByPosition($pos, 'empty');
                        $tableModel->updateTableNumberByPosition($pos, $newTableNumber);
                    } else {
                        $tableModel->updateTableNumberByPosition($pos, $newTableNumber);
                        // giữ nguyên status
                    }
                } else {
                    $tableModel->createTable([
                        'layoutPosition' => $pos,
                        'tableNumber' => $newTableNumber,
                        'status' => 'empty',
                    ]);
                }

                $counter++;
            }

            foreach ($existingMap as $pos => $table) {
                if (!isset($newLayoutSet[$pos])) {
                    if ($table['status'] === 'empty') {
                        $tableModel->updateStatusByPosition($pos, 'inactive');
                    }
                }
            }

            header("Location: /cnpm-final/TableController/manageTableLayout");
            exit;
        }


    }
?>

