import { ReactNode } from 'react'

type Props = {
    children?: ReactNode
}

const PostTitle = ({ children }: Props) => {
    return (
        <section className="flex-col md:flex-row flex items-center md:justify-between mt-12 mb-8 md:mb-12">
            <h1 className="text-6xl md:text-8xl tracking-tighter leading-tight md:pr-8">
                {children}
            </h1>
        </section>
    )
}

export default PostTitle
