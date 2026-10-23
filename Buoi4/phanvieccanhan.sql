-- 1. Lấy danh sách các phiếu mượn đang quá hạn chưa trả (Dùng WHERE)
SELECT id, nguoi_dung_id, ban_sao_id, ngay_muon, ngay_tra_du_kien
FROM phieu_muon
WHERE trang_thai = 'dang_muon' AND ngay_tra_du_kien < CURDATE();

-- 2. Xem chi tiết lịch sử mượn sách của một Độc giả theo Mã hoặc Email (Dùng JOIN + WHERE)
SELECT pm.id AS ma_phieu, nd.ho_ten AS ten_doc_gia, s.ten_sach, bss.ma_ban_sao, pm.ngay_muon, pm.trang_thai
FROM phieu_muon pm
JOIN nguoi_dung nd ON pm.nguoi_dung_id = nd.id
JOIN ban_sao_sach bss ON pm.ban_sao_id = bss.id
JOIN sach s ON bss.sach_id = s.id
WHERE nd.email = 'docgia01@example.com'
ORDER BY pm.ngay_muon DESC;

-- 3. Thống kê tổng số tiền phạt theo từng Độc giả đã vi phạm (Dùng JOIN + GROUP BY + WHERE)
SELECT nd.id, nd.ho_ten, nd.email, SUM(pm.tien_phat) AS tong_tien_phat
FROM phieu_muon pm
JOIN nguoi_dung nd ON pm.nguoi_dung_id = nd.id
WHERE pm.tien_phat > 0
GROUP BY nd.id, nd.ho_ten, nd.email;