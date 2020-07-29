import Avatar from './avatar'
import DateFormater from './date-formater'
import CoverImage from './cover-image'
import Link from 'next/link'
import Author from '../types/author'

type Props = {
    title: string
    coverImage: string
    date: string
    excerpt: string
    author: Author
    slug: string
}

const PostPreview = ({
    title,
    coverImage,
    date,
    excerpt,
    author,
    slug,
}: Props) => {
    return (
        <div>
            <div className="mb-5" style={{
                height: '10rem',
                overflow: 'hidden'
            }}>
                <CoverImage slug={slug} title={title} src={coverImage} />
            </div>
            <h3 className="text-3xl mb-3 leading-snug">
                <Link as={`/blog/posts/${slug}`} href="/blog/posts/[slug]">
                    <a className="hover:underline">{title}</a>
                </Link>
            </h3>
            <div className="text-lg mb-4">
                <DateFormater dateString={date} />
            </div>
            <p className="text-lg leading-relaxed mb-4">{excerpt}</p>
            <Avatar name={`${author.firstname} ${author.lastname}`} picture={author.picture.url} />
        </div>
    )
}

export default PostPreview
