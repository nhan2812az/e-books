import React, { useState, useEffect } from "react";
import axios from "axios";

const ReadBook = ({ bookId }) => {
  const [chapters, setChapters] = useState([]);
  const [currentChapter, setCurrentChapter] = useState(0);

  useEffect(() => {
    const fetchChapters = async () => {
      const response = await axios.get(`http://localhost:8000/api/chapters?book_id=${bookId}`);
      setChapters(response.data);
    };
    fetchChapters();
  }, [bookId]);

  const handleNextChapter = () => {
    if (currentChapter < chapters.length - 1) {
      setCurrentChapter(currentChapter + 1);
    }
  };

  const handlePrevChapter = () => {
    if (currentChapter > 0) {
      setCurrentChapter(currentChapter - 1);
    }
  };

  return (
    <div>
      <h2>{chapters[currentChapter]?.title}</h2>
      <p>{chapters[currentChapter]?.content}</p>
      <button onClick={handlePrevChapter} disabled={currentChapter === 0}>
        Prev
      </button>
      <button onClick={handleNextChapter} disabled={currentChapter === chapters.length - 1}>
        Next
      </button>
    </div>
  );
};

export default ReadBook;
