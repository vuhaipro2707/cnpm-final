<?php
$tablesByPos = [];
foreach ($data['table'] as $table) {
    $tablesByPos[$table['layoutPosition']] = $table;
}
?>

<div class="container my-4 d-flex justify-content-center gap-4">
    <div>
        <h3 class="mb-3">🧭 Quản lý sơ đồ bàn</h3>

        <form id="layoutForm" method="POST" action="">
            <input type="hidden" name="layoutData" id="layoutData">
            <button type="button" class="btn btn-warning mb-3" id="toggleEdit">🔧 Chế độ chỉnh sửa</button>
            <button type="submit" class="btn btn-success mb-3" id="saveBtn" style="display: none;">💾 Cập nhật Layout</button>

            <div class="border p-2 bg-light">
                <?php for ($row = 0; $row < 6; $row++): ?>
                    <div class="d-flex justify-content-center">
                        <?php for ($col = 0; $col < 6; $col++):
                            $pos = "{$row}_{$col}";
                            $hasTable = isset($tablesByPos[$pos]) && $tablesByPos[$pos]['status'] !== 'inactive';

                            $status = $hasTable ? $tablesByPos[$pos]['status'] : null;
                            $tableNumber = $hasTable ? $tablesByPos[$pos]['tableNumber'] : "";

                            $btnClass = "btn-outline-secondary";
                            if ($status === 'empty') $btnClass = "btn-empty";
                            elseif ($status === 'serving') $btnClass = "btn-serving";
                        ?>
                            <div class="table-wrapper">
                                <div class="btn <?= $btnClass ?> table-cell" data-pos="<?= $pos ?>">
                                    <?= htmlspecialchars($tableNumber) ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </form>
    </div>

    <!-- Ghi chú trạng thái -->
    
    <div class="card" style="min-width: 160px; height: 160px;">
        <div class="card-body">
            <h5 class="card-title">📝 Ghi chú trạng thái bàn</h5>
            <div class="d-flex align-items-center mb-2">
                <div class="legend-box btn-empty me-2"></div>
                <div>Trống (empty)</div>
            </div>
            <div class="d-flex align-items-center mb-2">
                <div class="legend-box btn-serving me-2"></div>
                <div>Đang phục vụ (serving)</div>
            </div>
            <div class="d-flex align-items-center mb-2">
                <div class="legend-box btn-outline-secondary me-2"></div>
                <div>Không phải bàn</div>
            </div>
        </div>
    </div>

</div>

<style>
    .table-wrapper {
        width: 60px;
        height: 60px;
        margin: 3px;
        display: inline-block;
    }

    .table-cell {
        width: 100%;
        height: 100%;
        font-size: 15px;
        font-weight: 500;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .btn-empty {
        background-color: #a8d5ba;
        color: #004d40;
        border: 1px solid #6fbf73;
    }

    .btn-serving {
        background-color: #2e7d32;
        color: white;
        border: 1px solid #27632a;
    }

    .legend-box {
        width: 25px;
        height: 25px;
        border-radius: 4px;
        border: 1px solid transparent;
    }

    .legend-box.btn-empty {
        background-color: #a8d5ba;
        border-color: #6fbf73;
    }

    .legend-box.btn-serving {
        background-color: #2e7d32;
        border-color: #27632a;
    }

    .legend-box.btn-outline-secondary {
        background-color: transparent;
        border-color: #6c757d;
    }
</style>

<script>
    let editMode = false;
    const toggleBtn = document.getElementById("toggleEdit");
    const saveBtn = document.getElementById("saveBtn");

    toggleBtn.addEventListener("click", () => {
    if (!editMode) {
        const hasServing = Array.from(document.querySelectorAll(".table-cell"))
            .some(cell => cell.classList.contains("btn-serving"));
        if (hasServing) {
            alert("❌ Không thể chỉnh sửa khi có bàn đang phục vụ!");
            return;
        }
    } else {
        location.reload();
        return;
    }

    editMode = !editMode;
    saveBtn.style.display = editMode ? 'inline-block' : 'none';
    toggleBtn.classList.toggle("btn-danger", editMode);
    toggleBtn.textContent = editMode ? "🚫 Thoát chỉnh sửa" : "🔧 Chế độ chỉnh sửa";
});


    document.querySelectorAll(".table-cell").forEach(cell => {
        cell.addEventListener("click", () => {
            if (!editMode) {
                // Khi KHÔNG ở chế độ chỉnh sửa => gửi POST với layoutPosition và tableNumber
                const layoutPosition = cell.dataset.pos;
                const tableNumber = cell.textContent.trim();

                // Nếu không có số bàn thì không gửi
                if (!tableNumber) return;

                const form = document.createElement("form");
                form.method = "POST";
                form.action = "/cnpm-final/OrderController/tableTrackOrder"; // <-- endpoint xử lý

                // Gửi layoutPosition
                const posInput = document.createElement("input");
                posInput.type = "hidden";
                posInput.name = "layoutPosition";
                posInput.value = layoutPosition;
                form.appendChild(posInput);

                // Gửi tableNumber
                const tnInput = document.createElement("input");
                tnInput.type = "hidden";
                tnInput.name = "tableNumber";
                tnInput.value = tableNumber;
                form.appendChild(tnInput);

                document.body.appendChild(form);
                form.submit();
                return;
            }
            if (cell.classList.contains("btn-serving")) return;

            if (cell.classList.contains("btn-empty")) {
                cell.classList.remove("btn-empty");
                cell.classList.add("btn-outline-secondary");
                cell.textContent = "";
            } else if (cell.classList.contains("btn-outline-secondary")) {
                cell.classList.remove("btn-outline-secondary");
                cell.classList.add("btn-empty");
                cell.textContent = "TBD";
            }
        });
    });

    document.getElementById("layoutForm").addEventListener("submit", () => {
        const cells = Array.from(document.querySelectorAll(".table-cell"));

        // Sắp xếp theo layout từ trái qua phải, trên xuống dưới
        cells.sort((a, b) => {
            const [rowA, colA] = a.dataset.pos.split('_').map(Number);
            const [rowB, colB] = b.dataset.pos.split('_').map(Number);
            return rowA !== rowB ? rowA - rowB : colA - colB;
        });

        const layout = [];
        let tableNumber = 1;

        cells.forEach(cell => {
            if (cell.classList.contains("btn-empty") || cell.classList.contains("btn-serving")) {
                const status = cell.classList.contains("btn-serving") ? "serving" : "empty";
                const label = "T" + tableNumber;
                cell.textContent = label;

                layout.push({
                    layoutPosition: cell.dataset.pos,
                    status: status,
                    tableNumber: label
                });

                tableNumber++;
            }
        });

        document.getElementById("layoutData").value = JSON.stringify(layout);
    });
</script>
