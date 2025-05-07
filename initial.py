import os

project_structure = {
    "app": {
        "controllers": {},
        "models": {},
        "views": {
            "layout": {
                "header.php": "",
                "footer.php": "",
                "sidebar.php": "",
            },
            "partials": {
                "alert.php": "",
                "menu_item.php": "",
                "table_row.php": "",
            },
            "customer": {
                "login.php": "",
                "order_menu.php": "",
                "track_order.php": "",
                "rate_item.php": "",
                "account.php": "",
            },
            "staff": {
                "dashboard.php": "",
                "manage_table.php": "",
                "update_order.php": "",
                "manage_customer.php": "",
                "print_receipt.php": "",
            },
            "manager": {
                "dashboard.php": "",
                "manage_menu.php": "",
                "track_metrics.php": "",
                "manage_employee.php": "",
                "track_inventory.php": "",
            },
            "admin": {
                "login.php": "",
                "system_monitor.php": "",
                "manage_roles.php": "",
                "backup_system.php": "",
            }
        }
    },
    "public": {
        "css": {},
        "js": {},
        "images": {}
    },
    "config": {},
    "core": {},
    "index.php": "<?php // Entry point"
}


def create_structure(base_path, structure):
    for name, content in structure.items():
        path = os.path.join(base_path, name)
        if isinstance(content, dict):
            os.makedirs(path, exist_ok=True)
            create_structure(path, content)
        else:
            with open(path, 'w', encoding='utf-8') as f:
                f.write(content)


# Run this to generate the project
create_structure("", project_structure)
