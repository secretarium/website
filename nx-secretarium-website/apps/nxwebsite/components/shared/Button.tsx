import React from 'react';

export interface ListItemProps {
  color: string;
  text: string
}

const Button = ({color, text}) => {
  return (
    <button className={`bg-${color} hover:bg-${color} text-white py-2 px-5 rounded-full`}>
      {text}
    </button>
  );
};

export default Button;
