import Layout from '../components/layout'
import Head from 'next/head'
import indexStyles from './index.module.css'
import imgFencedNetwork from '../public/assets/images/figure_fencedNetwork.svg';
import imgDCApp from '../public/assets/images/figure_dcApp.svg';
import imgShield from '../public/assets/images/figure_shield.svg';
import imgPuzzle from '../public/assets/images/figure_puzzle.svg';
import imgScale from '../public/assets/images/figure_scale.png';
import imgRestore from '../public/assets/images/figure_restore.svg';
import imgReaper from '../public/assets/images/figure_reaper.svg';
import imgMap from '../public/assets/images/figure_map.png';
import imgTechnology from '../public/assets/images/figure_technology.svg';
import imgSecretProcessing from '../public/assets/images/figure_secretProcessing.svg';
import imgSecretMixing from '../public/assets/images/figure_secretMixing.svg';
import imgLogoIntel from '../public/assets/images/logo_intel.svg';
import imgLogoSwisscom from '../public/assets/images/logo_swisscom.svg';
import imgLogoSocGen from '../public/assets/images/logo_soge.svg';
import imgLogoAnO from '../public/assets/images/logo_ano.svg';

const Index = () => {
    return (
        <>
            <Layout>
                <Head>
                    <title>Secretarium Blog</title>
                </Head>
                <section id="what-it-is">
                    <div className="container mx-auto px-5">
                        <h2 className="mt-8 mb-2 text-4xl lg:text-4xl tracking-tighter leading-tight">What is Secretarium&nbsp;?</h2>
                        <h3 className="mb-8 text-2xl lg:text-3xl tracking-tighter leading-tight text-red-500">Secretarium is an integrity and confidentiality crypto-platform</h3>
                        <div className="grid grid-cols-1 md:grid-cols-2 md:col-gap-16 lg:col-gap-32 row-gap-20 md:row-gap-32 mb-32 ">
                            <div className="px-0 pr-md-5">
                                <div className={`${indexStyles.secImgContainer} ${indexStyles.secImgFixedHeight}`}>
                                    <img src={imgFencedNetwork} alt="Confidential computing platform image" />
                                </div>
                                <h4 className="mb-2 uppercase text-gray-600">Distributed Confidential computing platform</h4>
                                <p>
                                    Relying on trusted execution environments and powered by secure multi-party
                                    computing, Secretarium has been designed to run applications on encrypted
                                    data in a trustable, distributed, scalable and efficient way, with no
                                    possible data leakage or manipulation, and no single point of failure.
							</p>
                            </div>
                            <div className="px-0 pl-md-5 mt-md-0">
                                <div className={`${indexStyles.secImgContainer} ${indexStyles.secImgFixedHeight}`}>
                                    <img src={imgDCApp} alt="DCApp image" />
                                </div>
                                <h4 className="mb-2 uppercase text-gray-600">Hosting Distributed Confidential Applications </h4>
                                <p>
                                    Distributed Confidential Applications (DCApps) are smart contracts systems
                                    with cryptographic proof of integrity.
                                    End-users can grant access to subsets of their private data to DCApps, and
                                    DCApps can request access to user's data for pre-defined specific processing.
							</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="what-it-does">
                    <div className="container mx-auto px-5">
                        <h2 className="mt-8 mb-2 text-4xl lg:text-4xl tracking-tighter leading-tight">What does Secretarium provide&nbsp;?</h2>
                        <h3 className="mb-8 text-2xl lg:text-3xl tracking-tighter leading-tight text-red-500">Total confidentiality: nobody sees the data, including Secretarium</h3>
                        <div className="grid grid-cols-1 md:grid-cols-2 md:col-gap-16 lg:col-gap-32 row-gap-20 md:row-gap-32 mb-32">
                            <div className="px-0 pr-md-5">
                                <div className={`${indexStyles.secImgContainer} ${indexStyles.secImgFixedHeight}`}>
                                    <img src={imgShield} alt="intellectual property image" />
                                </div>
                                <h4 className="mb-2 uppercase text-gray-600">Protects intellectual property and commercial rights</h4>
                                <p>
                                    Secretarium guarantees privacy by default and by design: users always keep
                                    control of their data. Secretarium uses end-to-end encryption: data
                                    uploaded to Secretarium remains the property of its originators and no one
                                    can access it in clear-text. DCApps intellectual property remains the
                                    property of the DCApp writer.
							</p>
                            </div>
                            <div className="px-0 pl-md-5 mt-md-0">
                                <div className={`${indexStyles.secImgContainer} ${indexStyles.secImgFixedHeight}`}>
                                    <img src={imgPuzzle} alt="easy integration image" />
                                </div>
                                <h4 className="mb-2 uppercase text-gray-600">Offers easy integration to your processes</h4>
                                <p>
                                    Every Secretarium node is coupled with a web server. Secretarium connection
                                    protocol integrates easily into recent browsers and tablets.
                                    Secretarium is real-time and has the capacity of pushing data to end-users.
                                    Secretarium is designed for speed: we achieve finality of execution within a
                                    split second, simplifying integration in an unparalleled way for a Blockchain.
							</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="scaling">
                    <div className="container mx-auto px-5 mb-32">
                        <h2 className="mt-8 mb-2 text-4xl lg:text-4xl tracking-tighter leading-tight">How does Secretarium scale&nbsp;?</h2>
                        <h3 className="mb-8 text-2xl lg:text-3xl tracking-tighter leading-tight text-red-500">Secretarium commoditises privacy at scale for application editors</h3>
                        <div className="row mx-0 mt-5">
                            <div className="px-0">
                                <h4 className="mb-2 uppercase text-gray-600">Facilitates confidential computing at scale</h4>
                                <p>
                                    The throughput and latency of a confidential computing system should be
                                    compatible with real-life scenarios. We believe in a system that can
                                    grow organically in the same way the internet did.
							</p>
                            </div>
                        </div>
                        <div className={`${indexStyles.secImgContainer}`}>
                            <img src={imgScale} alt="scale image" />
                        </div>
                    </div>
                </section>
                <section id="why">
                    <div className="container mx-auto px-5">
                        <h2 className="mt-8 mb-2 text-4xl lg:text-4xl tracking-tighter leading-tight">Why Secretarium&nbsp;?</h2>
                        <h3 className="mb-8 text-2xl lg:text-3xl tracking-tighter leading-tight text-red-500">A platform built for data privacy, ownership and control</h3>
                        <div className="grid grid-cols-1 md:grid-cols-2 md:col-gap-16 lg:col-gap-32 row-gap-20 md:row-gap-32 mb-32">
                            <div className="px-0 pr-md-5">
                                <div className={`${indexStyles.secImgContainer} ${indexStyles.secImgFixedHeight}`}>
                                    <img src={imgRestore} alt="restore right to privacy image" />
                                </div>
                                <h4 className="mb-2 uppercase text-gray-600">Restore the right to privacy for people and companies</h4>
                                <p>
                                    Consent, privacy by design and by default, are our DNA. We believe people and
                                    companies should have the option of using
                                    a technology that enforces their privacy.
							</p>
                            </div>
                            <div className="px-0 pl-md-5 mt-md-0">
                                <div className={`${indexStyles.secImgContainer} ${indexStyles.secImgFixedHeight}`}>
                                    <img src={imgReaper} alt="prevent scavenging image" />
                                </div>
                                <h4 className="mb-2 uppercase text-gray-600">Prevent scavenging and monetization of private data</h4>
                                <p>
                                    The internet was intended for driving collaboration, but the balance between
                                    data originators and data aggregators has been heavily tilted toward the latter.
                                    Our goal is to achieve the same level of service automation without disclosing
                                    of private data.
							</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="whom-for">
                    <div className="container mx-auto px-5 mb-32">
                        <h2 className="mt-8 mb-2 text-4xl lg:text-4xl tracking-tighter leading-tight">Who is Secretarium for&nbsp;?</h2>
                        <h3 className="mb-8 text-2xl lg:text-3xl tracking-tighter leading-tight text-red-500">Secretarium enables impartial and trustful exchanges between distrusting
                        parties.
                        Shared services are distributed and controlled multilaterally, preventing any
						single party from pulling the plug.</h3>
                        <div className={`${indexStyles.secImgContainer}`}>
                            <img src={imgMap} alt="map image" />
                        </div>
                    </div>
                </section>
                <section id="vision">
                    <div className="container mx-auto px-5">
                        <h2 className="mt-8 mb-2 text-4xl lg:text-4xl tracking-tighter leading-tight">Our story and vision</h2>
                        <h3 className="mb-8 text-2xl lg:text-3xl tracking-tighter leading-tight text-red-500">From technology, pharma, banking, cryptography, and restoring privacy for all</h3>
                        <div className="grid grid-cols-1 md:grid-cols-2 md:col-gap-16 lg:col-gap-32 row-gap-20 md:row-gap-32 mb-32">
                            <div className="px-0 pr-md-5">
                                <h4 className="mb-2 uppercase text-gray-600">Our story</h4>
                                <p>
                                    Secretarium founders are engineers who have worked for many years in challenging
								environments.<br />
								In 2014, they volunteered to create a blockchain lab for a tier-one European bank.
								The numerous prototypes and studies resulting from this experience evidenced the
								unsuitability of blockchains and DLTs when faced with confidentiality, performance
								and user experience constraints.<br />
								Early 2016, they started experiments in trusted execution environments and pivoted
								into confidential computing. At the end of 2016, recognizing the much wider
								applicability of confidential computing, Secretarium was founded.<br />
								Working closely with clients, we have designed and improved our technology from real
								business cases. Starting with Fintech, we are now actively extending our application
								base to Pharma and Bio-medical, with the goal of powering privacy-respecting medical
								progress.
							</p>
                            </div>
                            <div className="px-0 pl-md-5 mt-md-0">
                                <h4 className="mb-2 uppercase text-gray-600">Our vision and philosophy</h4>
                                <p>
                                    Secretarium seeks to restore the right to privacy for people and companies.<br />
								Our short term objectives are located in the Pharmatech, RegTech and InsurTech
								sectors, where we provide the infrastructure rails for secrets intermediation.<br />
								We envision Secretarium as the safe harbour for private data. Our goal is to
								simplify the setup and integration of privacy-respecting applications. We are
								building an open ecosystem where anyone can create, host, control and monetise
								applications powered by our technology.<br />
								We have issued a legally enforceable charter where we commit to preventing any
								usage of our technology facilitating illegal practices.<br />
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="technology">
                    <div className="container mx-auto px-5">
                        <h2 className="mt-8 mb-2 text-4xl lg:text-4xl tracking-tighter leading-tight">How does Secretarium work&nbsp;?</h2>
                        <h3 className="mb-8 text-2xl lg:text-3xl tracking-tighter leading-tight text-red-500">Secretarium leverages cryptography and trusted execution environments</h3>
                        <div className="grid grid-cols-1 md:grid-cols-2 md:col-gap-16 lg:col-gap-32 row-gap-20 md:row-gap-32 mb-32">
                            <div className="px-0 pr-md-2">
                                <h4 className="mb-2 uppercase text-gray-600">Confidentiality out of the box</h4>
                                <p>
                                    We designed a secure connection protocol to guarantee to end-users the integrity
                                    of the remote nodes. Our
                                    crypto-platform is designed to facilitate the development of the business logic
                                    inside distributed confidential
                                    applications, so you don’t have to deal with all the complexity.
							</p>
                                <h4 className="mt-5 mb-2 uppercase text-gray-600">Peer-to-peer network</h4>
                                <p>
                                    We set-up multiple trusted nodes to communicate with one another over an
                                    encrypted peer to peer network, without a
                                    single point of failure. Using a protocol inspired by the military aviation that
                                    we call "identification friend-or-foe",
                                    we guarantee the integrity of the network at all times.
							</p>
                                <h4 className="mt-5 mb-2 uppercase text-gray-600">Secure hardware</h4>
                                <p>
                                    To avoid security vs performance trade-offs, we use trusted execution
                                    environments. Encrypted enclaves on secure
                                    hardware provide privacy and integrity, even to an attacker with physical access
                                    to the machine and operating system.
							</p>
                            </div>
                            <div className="px-0">
                                <div className={`${indexStyles.secImgContainer} ${indexStyles.secImgTechStack}`}>
                                    <img src={imgTechnology} alt="technology image" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="secretive-insight">
                    <div className="container mx-auto px-5">
                        <h2 className="mt-8 mb-2 text-4xl lg:text-4xl tracking-tighter leading-tight">Secretive insight</h2>
                        <h3 className="mb-8 text-2xl lg:text-3xl tracking-tighter leading-tight text-red-500">Build insight on data, without disclosing the data</h3>
                        <div className="grid grid-cols-1 md:grid-cols-2 md:col-gap-16 lg:col-gap-32 row-gap-20 md:row-gap-32 mb-32">
                            <div className="px-0 pr-md-5">
                                <div className={`${indexStyles.secImgContainer} ${indexStyles.secImgFixedHeight}`}>
                                    <img src={imgSecretProcessing} alt="secret processing image" />
                                </div>
                                <h4 className="mb-2 uppercase text-gray-600">Secret processing</h4>
                                <p>
                                    Some parties have proprietary algorithms, other parties have confidential
                                    data-banks. Secret processing involves combining both while guaranteeing
                                    secrecy.
                                    Designed for data analytics, it can be used for genomic diagnostic, either
                                    by individuals, or by medical firm over the DNA bank of another firm.
                                    When secured with Differential Privacy techniques, it is perfectly adapted for
                                    data rental, a new way of monetizing confidential data.
							</p>
                            </div>
                            <div className="px-0 pl-md-5 mt-md-0">
                                <div className={`${indexStyles.secImgContainer} ${indexStyles.secImgFixedHeight}`}>
                                    <img src={imgSecretMixing} alt="secret mixing image" />
                                </div>
                                <h4 className="mb-2 uppercase text-gray-600">Secret mixing</h4>
                                <p>
                                    Allows a group to pool private data together and collectively achieve insight.
                                    Designed for data pooling and data matching, it can be used to compute market
                                    data benchmarks, detect fraudulent insurance claims, compare reference data.
                                    Other usages involve enforcement of statistical secrecy, like the pricing of
                                    insurance policies on real data.
                                    Finally, it is a solution to the voting problem, Secretarium being able to
                                    enforce voting secrecy, voting protocol and a guaranteed honest outcome.
							</p>
                            </div>
                        </div>
                    </div>
                </section>
                <section id="sponsors">
                    <div className="container mx-auto px-5">
                        <h2 className="mt-8 mb-2 text-4xl lg:text-4xl tracking-tighter leading-tight">Our sponsors and partners</h2>
                        <h3 className="mb-8 text-2xl lg:text-3xl tracking-tighter leading-tight text-red-500">Reputable partners are helping us growing</h3>
                        <div className="grid grid-cols-1 md:grid-cols-3 md:col-gap-16 lg:col-gap-32 row-gap-20 md:row-gap-32 mb-32">
                            <div className="px-0 pr-0 pr-md-4">
                                <h4 className="mb-3 uppercase text-gray-600">Intel Corporation</h4>
                                <div className="comp-logo">
                                    <img src={imgLogoIntel} />
                                </div>
                                <p className="mt-3">
                                    Intel supports Secretarium with sponsored hardware, early access,
                                    support with engineers, infrastructure and sales teams.
							</p>
                            </div>
                            <div className="px-0 px-md-4 mt-md-0">
                                <h4 className="mb-3 uppercase text-gray-600">Swisscom</h4>
                                <div className={indexStyles.companyLogo}>
                                    <img src={imgLogoSwisscom} />
                                </div>
                                <p className="mt-3">
                                    Swisscom supports Secretarium with engineers, business developers,
                                    infrastructure. Our partnership includes a comprehensive joined go-to-market
                                    agreement, as well as a mutually approach to engage with large institutions.
							</p>
                            </div>
                            <div className="px-0 pl-0 pl-md-4 mt-md-0">
                                <h4 className="mb-3 uppercase text-gray-600">Société Générale</h4>
                                <div className={indexStyles.companyLogo}>
                                    <img src={imgLogoSocGen} />
                                </div>
                                <p className="mt-3">
                                    Société Générale UK hosts Secretarium in its London incubator “the Greenhouse”.
                                    This partnership grants us access to Société Générale's business leaders,
                                    influencers and specialists.
							</p>
                            </div>
                            <div className="px-0 pl-0 pl-md-4 mt-md-0">
                                <h4 className="mb-3 uppercase text-gray-600">Allen &amp; Overy</h4>
                                <div className={indexStyles.companyLogo}>
                                    <img src={imgLogoAnO} />
                                </div>
                                <p className="mt-3">
                                    Allen &amp; Overy UK hosts Secretarium in its London incubator “FUSE”.
                                    This partnership grants us access to A&amp;O's lawyers and clients to co-design
                                    new Legaltech initiatives.							</p>
                            </div>
                        </div>
                    </div>
                </section>
            </Layout>
        </>
    )
}

export default Index
