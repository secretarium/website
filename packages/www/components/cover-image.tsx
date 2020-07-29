import cn from 'classnames'
import Link from 'next/link'
import { motion } from 'framer-motion';

type Props = {
    title: string
    src: string
    slug?: string
}

const CoverImage = ({ title, src, slug }: Props) => {
    const image = (
        <motion.img
            layoutId={slug}
            src={src}
            alt={`Cover Image for ${title}`}
            className={cn('shadow-small', {
                'hover:shadow-medium transition-shadow duration-200': slug,
            })}
        />
    )
    return (
        <div className="-mx-5 sm:mx-0">
            {slug ? (
                <Link as={`/blog/posts/${slug}`} href="/blog/posts/[slug]">
                    <a aria-label={title}>{image}</a>
                </Link>
            ) : (
                    image
                )}
        </div>
    )
}

export default CoverImage
