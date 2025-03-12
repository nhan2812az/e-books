// src/components/Input.js
import React from 'react';

const Input = ({ type, name, value, onChange, placeholder }) => {
  return (
    <div className="input-group">
      <input
        type={type}
        name={name}
        value={value}
        onChange={onChange}
        placeholder={placeholder}
        required
      />
    </div>
  );
};

export default Input;
