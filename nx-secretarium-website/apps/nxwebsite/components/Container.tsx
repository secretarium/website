import * as React from 'react';
import cn from 'classnames';

export interface ContainerProps {}

const Container = (props: any) => {
  return <div className={cn('container mx-auto')} {...props} />;
};
 
export default Container;
