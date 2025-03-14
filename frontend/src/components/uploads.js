// src/components/UploadEbook.js
import React, { useState } from "react";
import axios from "axios";

const UploadEbook = () => {
  const [file, setFile] = useState(null);
  const [message, setMessage] = useState("");

  const handleFileChange = (e) => {
    setFile(e.target.files[0]);
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    const formData = new FormData();
    formData.append("ebook", file);

    try {
      const response = await axios.post("http://localhost:8000/api/books/uploads", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
      setMessage(response.data.message); // Hiển thị thông báo từ backend
    } catch (error) {
      // Hiển thị lỗi chi tiết từ backend (nếu có)
      if (error.response) {
        setMessage(`Lỗi: ${error.response.data.error || error.response.data.message}`);
      } else {
        setMessage("Có lỗi xảy ra khi tải lên e-book.");
      }
    }
  };

  return (
    <div>
      <h2>Upload E-Book</h2>
      <form onSubmit={handleSubmit}>
        <input type="file" onChange={handleFileChange} />
        <button type="submit">Tải lên</button>
      </form>
      {message && <p>{message}</p>} {/* Hiển thị thông báo */}
    </div>
  );
};

export default UploadEbook;
