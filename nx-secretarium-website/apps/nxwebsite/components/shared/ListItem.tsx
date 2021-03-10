import React from 'react';
export interface ListItemProps {
  number: string;
  initial_snippet: string;
  bold_snippet: string;
  last_snippet: string;
}

const ListItem = ({
  number,
  initial_snippet,
  bold_snippet,
  last_snippet,
}) => {
  return (
    <>
      <a className="mb-1">
        <span className="text-red-600 w-4 h-4 mr-3 rounded-full inline-flex items-center justify-center text-2xl">
          {number}
        </span>
        {initial_snippet} <strong>{bold_snippet}</strong> {last_snippet}
      </a>
    </>
  );
};

export default ListItem;
