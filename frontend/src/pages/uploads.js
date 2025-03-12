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
      const response = await axios.post("http://localhost:8000/api/upload", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });
      setMessage(response.data.message);
    } catch (error) {
      setMessage("Có lỗi xảy ra khi tải lên");
    }
  };

  return (
    <div>
      <h2>Upload E-Book</h2>
      <form onSubmit={handleSubmit}>
        <input type="file" onChange={handleFileChange} />
        <button type="submit">Tải lên</button>
      </form>
      {message && <p>{message}</p>}
    </div>
  );
};

export default UploadEbook;
