
## 🚌 **Bus-HCM – Hệ thống Quản lý Xe Buýt TP.HCM (dùng ArangoDB)**

### 🎯 **Mục tiêu của hệ thống**

Hệ thống **Bus-HCM** được xây dựng nhằm cung cấp một phương pháp có hệ thống để quản lý toàn bộ hoạt động vận hành xe buýt, bao gồm:

* Người  **quản lý bến xe (Admin)** ,
* **Tài xế (Driver)** ,
* **Phụ xe (Conductor)** ,
* và  **Hành khách (Passenger)** .

### ⚙️ **Chức năng chính**

#### 👨‍💼 **Người quản lý bến xe (Admin)**

* Có thể truy cập **tất cả dữ liệu** trong hệ thống.
* Thêm, sửa, hoặc xoá **tài xế** và  **phụ xe** .
* Phân công **chuyến đi (Trip)** cho tài xế và phụ xe.
* Tạo mới thông tin **xe buýt (Bus)** và  **lịch trình (Timetable)** .
* Theo dõi tổng doanh thu và số vé bán được từ từng chuyến.

#### 👨‍✈️ **Tài xế (Driver)**

* Đăng nhập vào hệ thống bằng tài khoản được cấp.
* Xem danh sách  **chuyến đi được phân công** .
* Nhập  **thời gian khởi hành** , **thời gian đến thực tế** và **lượng nhiên liệu sử dụng** cho mỗi chuyến.

#### 🧍‍♂️ **Phụ xe (Conductor)**

* Đăng nhập vào hệ thống bằng ID được phân công.
* Xem  **chuyến đi phụ trách** .
* Thực hiện  **bán vé cho hành khách** , ghi nhận:
  * Số điện thoại hành khách
  * Điểm đón và điểm đến
  * Giá vé
* Sau khi kết thúc chuyến đi, nhập **doanh thu tổng** và  **số vé đã bán** .

#### 🧑‍🤝‍🧑 **Hành khách (Passenger)**

* Cung cấp thông tin cơ bản để mua vé (qua phụ xe).
* Nhận được **mã vé (Ticket ID)** tự động sinh ra trong hệ thống.
