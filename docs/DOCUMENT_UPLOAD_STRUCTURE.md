# Test Document Upload

## Cấu trúc lưu file đã được chuẩn hóa

### Admin Upload (FileController):
- **Có Subject ID**: Lưu vào `storage/app/public/documents/{subject_id}/`
- **Không có Subject ID**: Lưu vào `storage/app/public/documents/{folder}/`
  - Folder options: general, lecture, exam, homework

### Teacher Upload (DocumentController):
- **Luôn lưu theo Subject**: `storage/app/public/documents/{subject_id}/`

### Ví dụ:
```
Admin upload file cho Subject Toán (ID=1):
→ storage/app/public/documents/1/1734360000_tailwind-guide.pdf

Admin upload file không chọn subject, chọn folder=general:
→ storage/app/public/documents/general/1734360000_system-manual.pdf

Teacher upload file cho Subject PHP (ID=4):
→ storage/app/public/documents/4/1734360000_laravel-notes.pdf
```

### Test ngay:

1. Truy cập: http://127.0.0.1:8000/admin/files/upload
2. Upload một file mới với đầy đủ thông tin:
   - Title: Test File
   - Subject: chọn một subject (VD: Toán)
   - Folder: general/lecture/exam/homework
   - File: chọn một file bất kỳ
3. Sau khi upload thành công, truy cập trang materials của student
4. File mới sẽ có thể download được ngay!

### Kiểm tra file đã upload:

```bash
php scripts/check-document.php [document_id]
```

Hoặc xem tất cả files:
```bash
php scripts/fix-missing-documents.php
```
