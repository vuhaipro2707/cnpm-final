
# CNPM Final Project

## 🚀 Hướng dẫn cài đặt và chạy project

### ✅ Yêu cầu

- Đã cài đặt [XAMPP](https://www.apachefriends.org/index.html) trên máy tính.

---

### 🛠 Các bước cài đặt

#### 1. Cài đặt XAMPP (nếu chưa có)
- Truy cập: [https://www.apachefriends.org/index.html](https://www.apachefriends.org/index.html)
- Tải về và cài đặt phiên bản phù hợp với hệ điều hành của bạn (Windows, macOS, Linux).

#### 2. Clone project vào thư mục `htdocs`
```bash
cd /path/to/xampp/htdocs
git clone https://github.com/vuhaipro2707/cnpm-final.git
```

#### 3. Khởi tạo cơ sở dữ liệu
- Mở **XAMPP Control Panel**, bật **Apache** và **MySQL**.
- Truy cập [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
- Tạo database mới, đặt tên là: `cnpm_final`
- Chọn database vừa tạo → Chọn tab **Import**
- Chọn file `init.sql` từ thư mục dự án → Nhấn **Go**

#### 4. Chạy project
Truy cập trình duyệt và nhập đường dẫn sau:
```
http://localhost/cnpm-final/
```

---

## 🔐 Tài khoản dùng để test

| Vai trò   | Tên đăng nhập | Mật khẩu |
|-----------|---------------|----------|
| Staff     | `staff`       | `1`      |
| Manager   | `manager`     | `1`      |
| Customer  | `customer`    | `1`      |

---

## 📁 Cấu trúc thư mục chính

```bash
cnpm-final/
├── init.sql            # File khởi tạo database
├── index.php           # Trang chính để chạy project
├── ...
```

---

## 📞 Liên hệ

Nếu có bất kỳ vấn đề nào khi cài đặt hoặc sử dụng project, vui lòng liên hệ:

**Email:** vuhaipro2707@gmail.com
