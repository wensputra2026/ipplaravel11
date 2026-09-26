import React, { useState, useMemo } from 'react';
import { Head, router } from '@inertiajs/react';

export default function FrontendIndex({
    appName,
    schoolName,
    schoolLogo,
    copyright,
    totalSiswa,
    totalLaki,
    totalPerempuan,
    totalKelas,
    tahunAktif,
    semesterAktif,
    daftarTahun = [],
    kategoriDetail = {},
    distJk = {},
    distAgama = {},
    distKategori = {},
    distIpp = {},
    distPekerjaanAyah = {},
    distPekerjaanIbu = {},
    distPenghasilanAyah = {},
    distPenghasilanIbu = {},
    distTanggunganSiswa = {},
    distTanggunganKeluarga = {},
    families = [],
    classProgress = [],
    auth = {}
}) {
    const total = Math.max(1, totalSiswa || 0);

    // Filter state
    const [selectedTa, setSelectedTa] = useState(tahunAktif || '');
    const [selectedSem, setSelectedSem] = useState(semesterAktif || '');

    // Search filter state for family table
    const [searchQuery, setSearchQuery] = useState('');

    // Tab state for parent jobs & income
    const [jobTab, setJobTab] = useState('ayah');
    const [incomeTab, setIncomeTab] = useState('ayah');

    // Handle filter submit
    const handleFilter = (e) => {
        if (e) e.preventDefault();
        router.get('/', {
            tahun_ajaran: selectedTa,
            semester: selectedSem
        }, {
            preserveState: true,
            preserveScroll: true
        });
    };

    const handleTaChange = (val) => {
        setSelectedTa(val);
        router.get('/', {
            tahun_ajaran: val,
            semester: selectedSem
        }, {
            preserveState: true,
            preserveScroll: true
        });
    };

    const handleSemChange = (val) => {
        setSelectedSem(val);
        router.get('/', {
            tahun_ajaran: selectedTa,
            semester: val
        }, {
            preserveState: true,
            preserveScroll: true
        });
    };

    // Filtered families list based on search
    const filteredFamilies = useMemo(() => {
        if (!searchQuery.trim()) return families;
        const q = searchQuery.toLowerCase();
        return families.filter(f => {
            const headMatch = (f.kepala_keluarga || '').toLowerCase().includes(q);
            const studentMatch = (f.siswa_list || []).some(s => s.toLowerCase().includes(q));
            return headMatch || studentMatch;
        });
    }, [families, searchQuery]);

    const totalFamiliesCount = useMemo(() => {
        return Object.values(distTanggunganKeluarga || {}).reduce((a, b) => a + Number(b), 0) || 1;
    }, [distTanggunganKeluarga]);

    return (
        <div style={{ backgroundColor: '#f8fafc', minHeight: '100vh', color: '#0f172a', fontFamily: "'Outfit', sans-serif" }}>
            <Head title={`Beranda - ${schoolName} ${appName}`} />

            {/* Top Navbar */}
            <nav className="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top py-2 py-sm-3 shadow-xs" style={{ backdropFilter: 'blur(12px)', backgroundColor: 'rgba(255, 255, 255, 0.95)' }}>
                <div className="container-fluid px-3 px-md-4">
                    <div className="d-flex align-items-center justify-content-between w-100">
                        <a href="/" className="d-flex align-items-center gap-2 text-decoration-none">
                            <div className="p-1 bg-white rounded-3 border shadow-xs">
                                <img
                                    src={`/assets/dist/img/${schoolLogo || 'logo_1767853884.png'}`}
                                    alt="Logo"
                                    width="38"
                                    height="38"
                                    className="rounded"
                                    style={{ objectFit: 'contain' }}
                                />
                            </div>
                            <div>
                                <div className="fw-bold text-dark lh-sm" style={{ fontSize: '1.05rem', letterSpacing: '-0.01em' }}>{schoolName}</div>
                                <div className="text-secondary small fw-medium" style={{ fontSize: '0.78rem' }}>{appName} &bull; Portal Publik (Inertia React)</div>
                            </div>
                        </a>

                        <div className="d-flex align-items-center gap-2">
                            <span className="badge bg-light text-secondary border px-3 py-2 rounded-pill small d-none d-md-inline-block">
                                <i className="bi bi-calendar-check text-primary me-1"></i> TA: {tahunAktif || 'Semua'} ({semesterAktif || '-'})
                            </span>

                            {auth?.user ? (
                                <a href="/dashboard" className="btn btn-primary btn-sm px-3 shadow-sm d-inline-flex align-items-center gap-1" style={{ borderRadius: '8px', fontWeight: 600 }}>
                                    <i className="bi bi-speedometer2"></i> Dashboard
                                </a>
                            ) : (
                                <a href="/login" className="btn btn-primary btn-sm px-3 shadow-sm d-inline-flex align-items-center gap-1" style={{ borderRadius: '8px', fontWeight: 600 }}>
                                    <i className="bi bi-box-arrow-in-right"></i> Login
                                </a>
                            )}
                        </div>
                    </div>
                </div>
            </nav>

            {/* Hero Header Banner */}
            <header style={{
                background: 'radial-gradient(100% 120% at 50% 0%, #eff6ff 0%, #f8fafc 65%, #f1f5f9 100%)',
                borderBottom: '1px solid #e2e8f0',
                padding: '3rem 1rem 2.5rem',
                textAlign: 'center'
            }}>
                <div className="container">
                    <span className="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill mb-3 fw-semibold shadow-xs">
                        <i className="bi bi-shield-check me-1"></i> Sistem Informasi Transparansi & Rekapitulasi Data Siswa
                    </span>
                    <h1 className="display-6 fw-bold mb-2 text-dark" style={{ letterSpacing: '-0.025em' }}>
                        Rekapitulasi Data Siswa & Kategori Pembiayaan
                    </h1>
                    <p className="lead text-secondary mx-auto mb-4" style={{ maxWidth: '680px', fontSize: '1rem' }}>
                        Portal resmi informasi profil akademik, kondisi sosial ekonomi, dan kategorisasi iuran pendidikan siswa <strong>{schoolName}</strong>.
                    </p>

                    {/* Interactive Filter Box */}
                    <div className="bg-white border rounded-4 shadow-sm p-3 p-sm-4 mx-auto text-start" style={{ maxWidth: '680px', borderColor: '#e2e8f0' }}>
                        <form onSubmit={handleFilter} className="row g-3 align-items-end">
                            <div className="col-sm-5">
                                <label className="form-label small fw-semibold text-dark mb-1">
                                    <i className="bi bi-calendar3 text-primary me-1"></i> Tahun Ajaran
                                </label>
                                <select
                                    className="form-select form-select-sm"
                                    value={selectedTa}
                                    onChange={(e) => handleTaChange(e.target.value)}
                                    style={{ borderRadius: '8px' }}
                                >
                                    <option value="">Semua Tahun Ajaran</option>
                                    {daftarTahun.map((ta) => (
                                        <option key={ta} value={ta}>{ta}</option>
                                    ))}
                                </select>
                            </div>

                            <div className="col-sm-4">
                                <label className="form-label small fw-semibold text-dark mb-1">
                                    <i className="bi bi-clock-history text-primary me-1"></i> Semester
                                </label>
                                <select
                                    className="form-select form-select-sm"
                                    value={selectedSem}
                                    onChange={(e) => handleSemChange(e.target.value)}
                                    style={{ borderRadius: '8px' }}
                                >
                                    <option value="">Pilih Semester</option>
                                    <option value="Ganjil">Ganjil</option>
                                    <option value="Genap">Genap</option>
                                </select>
                            </div>

                            <div className="col-sm-3">
                                <button type="submit" className="btn btn-primary btn-sm w-100 py-2 justify-content-center shadow-xs" style={{ borderRadius: '8px', fontWeight: 600 }}>
                                    <i className="bi bi-funnel-fill me-1"></i> Terapkan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </header>

            {/* Main Content Body */}
            <main className="container-fluid px-3 px-md-4 py-4">

                {/* 4 Key Metric Stat Cards */}
                <div className="row g-3 mb-4">
                    {/* Total Siswa */}
                    <div className="col-12 col-sm-6 col-lg-3">
                        <div className="bg-white border rounded-4 p-3 d-flex align-items-center gap-3 shadow-xs h-100" style={{ borderColor: '#e2e8f0' }}>
                            <div className="d-flex align-items-center justify-content-center rounded-3 fs-4 flex-shrink-0" style={{ width: '52px', height: '52px', background: '#eff6ff', color: '#2563eb', border: '1px solid #bfdbfe' }}>
                                <i className="bi bi-people-fill"></i>
                            </div>
                            <div>
                                <div className="text-secondary small fw-semibold text-uppercase" style={{ fontSize: '0.74rem' }}>Total Siswa</div>
                                <div className="fw-bold text-dark fs-3 lh-sm">{Number(totalSiswa || 0).toLocaleString()}</div>
                                <div className="text-muted small" style={{ fontSize: '0.76rem' }}>Peserta Didik Aktif</div>
                            </div>
                        </div>
                    </div>

                    {/* Siswa Laki-Laki */}
                    <div className="col-12 col-sm-6 col-lg-3">
                        <div className="bg-white border rounded-4 p-3 d-flex align-items-center gap-3 shadow-xs h-100" style={{ borderColor: '#e2e8f0' }}>
                            <div className="d-flex align-items-center justify-content-center rounded-3 fs-4 flex-shrink-0" style={{ width: '52px', height: '52px', background: '#ecfeff', color: '#0891b2', border: '1px solid #a5f3fc' }}>
                                <i className="bi bi-gender-male"></i>
                            </div>
                            <div>
                                <div className="text-secondary small fw-semibold text-uppercase" style={{ fontSize: '0.74rem' }}>Siswa Laki-Laki</div>
                                <div className="fw-bold text-dark fs-3 lh-sm">{Number(totalLaki || 0).toLocaleString()}</div>
                                <div className="text-muted small" style={{ fontSize: '0.76rem' }}>
                                    {totalSiswa > 0 ? Math.round((totalLaki / totalSiswa) * 1000) / 10 : 0}% dari total
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Siswa Perempuan */}
                    <div className="col-12 col-sm-6 col-lg-3">
                        <div className="bg-white border rounded-4 p-3 d-flex align-items-center gap-3 shadow-xs h-100" style={{ borderColor: '#e2e8f0' }}>
                            <div className="d-flex align-items-center justify-content-center rounded-3 fs-4 flex-shrink-0" style={{ width: '52px', height: '52px', background: '#fff1f2', color: '#e11d48', border: '1px solid #fecdd3' }}>
                                <i className="bi bi-gender-female"></i>
                            </div>
                            <div>
                                <div className="text-secondary small fw-semibold text-uppercase" style={{ fontSize: '0.74rem' }}>Siswa Perempuan</div>
                                <div className="fw-bold text-dark fs-3 lh-sm">{Number(totalPerempuan || 0).toLocaleString()}</div>
                                <div className="text-muted small" style={{ fontSize: '0.76rem' }}>
                                    {totalSiswa > 0 ? Math.round((totalPerempuan / totalSiswa) * 1000) / 10 : 0}% dari total
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Total Kelas */}
                    <div className="col-12 col-sm-6 col-lg-3">
                        <div className="bg-white border rounded-4 p-3 d-flex align-items-center gap-3 shadow-xs h-100" style={{ borderColor: '#e2e8f0' }}>
                            <div className="d-flex align-items-center justify-content-center rounded-3 fs-4 flex-shrink-0" style={{ width: '52px', height: '52px', background: '#f0fdf4', color: '#16a34a', border: '1px solid #bbf7d0' }}>
                                <i className="bi bi-easel-fill"></i>
                            </div>
                            <div>
                                <div className="text-secondary small fw-semibold text-uppercase" style={{ fontSize: '0.74rem' }}>Total Kelas</div>
                                <div className="fw-bold text-dark fs-3 lh-sm">{Number(totalKelas || 0).toLocaleString()}</div>
                                <div className="text-muted small" style={{ fontSize: '0.76rem' }}>Rombongan Belajar</div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Kategorisasi Khusus */}
                <div className="bg-white border rounded-4 shadow-sm mb-4" style={{ borderColor: '#e2e8f0' }}>
                    <div className="px-4 py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5 className="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style={{ fontSize: '1rem' }}>
                            <i className="bi bi-bookmark-star-fill text-primary"></i> Kategorisasi Khusus Siswa
                        </h5>
                        <span className="badge bg-light text-secondary border px-3 py-1 rounded-pill small">
                            Prioritas Bantuan & Afirmasi
                        </span>
                    </div>
                    <div className="p-3 p-md-4">
                        <div className="row g-3">
                            <div className="col-sm-6 col-md-4 col-lg">
                                <div className="p-3 bg-light border rounded-3 d-flex align-items-center justify-content-between h-100">
                                    <div>
                                        <span className="d-block text-secondary small fw-semibold">a. Anak Panti Asuhan</span>
                                        <span className="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill mt-1" style={{ fontSize: '0.7rem' }}>Afirmasi Panti</span>
                                    </div>
                                    <div className="fs-4 fw-bold text-dark">{kategoriDetail?.panti_asuhan || 0}</div>
                                </div>
                            </div>

                            <div className="col-sm-6 col-md-4 col-lg">
                                <div className="p-3 bg-light border rounded-3 d-flex align-items-center justify-content-between h-100">
                                    <div>
                                        <span className="d-block text-secondary small fw-semibold">b. Anak Korban Bencana</span>
                                        <span className="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill mt-1" style={{ fontSize: '0.7rem' }}>Tanggap Bencana</span>
                                    </div>
                                    <div className="fs-4 fw-bold text-dark">{kategoriDetail?.korban_bencana || 0}</div>
                                </div>
                            </div>

                            <div className="col-sm-6 col-md-4 col-lg">
                                <div className="p-3 bg-light border rounded-3 d-flex align-items-center justify-content-between h-100">
                                    <div>
                                        <span className="d-block text-secondary small fw-semibold">c. Anak Terlantar</span>
                                        <span className="badge rounded-pill mt-1" style={{ fontSize: '0.7rem', color: '#7c3aed', background: '#f5f3ff', border: '1px solid #ddd6fe' }}>Sosial Terlantar</span>
                                    </div>
                                    <div className="fs-4 fw-bold text-dark">{kategoriDetail?.terlantar || 0}</div>
                                </div>
                            </div>

                            <div className="col-sm-6 col-md-6 col-lg">
                                <div className="p-3 bg-light border rounded-3 d-flex align-items-center justify-content-between h-100">
                                    <div>
                                        <span className="d-block text-secondary small fw-semibold">d. Ortu Berkebutuhan Khusus</span>
                                        <span className="badge bg-info-subtle text-info border border-info-subtle rounded-pill mt-1" style={{ fontSize: '0.7rem' }}>Disabilitas</span>
                                    </div>
                                    <div className="fs-4 fw-bold text-dark">{kategoriDetail?.ortu_abk || 0}</div>
                                </div>
                            </div>

                            <div className="col-sm-6 col-md-6 col-lg">
                                <div className="p-3 bg-light border rounded-3 d-flex align-items-center justify-content-between h-100">
                                    <div>
                                        <span className="d-block text-secondary small fw-semibold">e. Ortu Sakit Menahun</span>
                                        <span className="badge bg-secondary-subtle text-secondary border rounded-pill mt-1" style={{ fontSize: '0.7rem' }}>Kesehatan</span>
                                    </div>
                                    <div className="fs-4 fw-bold text-dark">{kategoriDetail?.ortu_sakit_menahun || 0}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Progres Pengisian Data Per Kelas */}
                {classProgress && classProgress.length > 0 && (
                    <div className="bg-white border rounded-4 shadow-sm mb-4" style={{ borderColor: '#e2e8f0', overflow: 'hidden' }}>
                        <div className="px-4 py-3 border-bottom d-flex align-items-center justify-content-between">
                            <h5 className="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style={{ fontSize: '1rem' }}>
                                <i className="bi bi-bar-chart-steps text-primary"></i> Progres Pengisian Data Per Kelas
                            </h5>
                            <span className="badge bg-light text-secondary border px-3 py-1 rounded-pill small">
                                {classProgress.length} Rombel
                            </span>
                        </div>
                        <div className="table-responsive">
                            <table className="table table-hover align-middle mb-0" style={{ fontSize: '0.88rem' }}>
                                <thead className="table-light">
                                    <tr>
                                        <th className="ps-4">Kelas</th>
                                        <th className="text-end">Total Siswa</th>
                                        <th className="text-end">Lengkap</th>
                                        <th className="text-end">Belum Lengkap</th>
                                        <th className="pe-4" style={{ minWidth: '200px' }}>Progres Kelengkapan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {classProgress.map((row, idx) => {
                                        const pct = Number(row.avg_percentage || 0);
                                        const color = pct >= 80 ? 'bg-success' : pct >= 50 ? 'bg-warning' : 'bg-danger';
                                        return (
                                            <tr key={idx}>
                                                <td className="ps-4 fw-bold text-dark">
                                                    <span className="badge bg-light text-dark border me-1">{row.nama_kelas}</span>
                                                </td>
                                                <td className="text-end fw-bold">{row.total}</td>
                                                <td className="text-end">
                                                    <span className="badge bg-success-subtle text-success border border-success-subtle">
                                                        <i className="bi bi-check2"></i> {row.complete} ({row.complete_pct}%)
                                                    </span>
                                                </td>
                                                <td className="text-end">
                                                    <span className="badge bg-danger-subtle text-danger border border-danger-subtle">
                                                        <i className="bi bi-x"></i> {row.incomplete}
                                                    </span>
                                                </td>
                                                <td className="pe-4">
                                                    <div className="d-flex align-items-center gap-2">
                                                        <div className="progress flex-grow-1" style={{ height: '6px', borderRadius: '999px', backgroundColor: '#e2e8f0' }}>
                                                            <div className={`progress-bar ${color} rounded-pill`} style={{ width: `${pct}%` }}></div>
                                                        </div>
                                                        <span className="fw-semibold text-secondary small text-nowrap" style={{ minWidth: '55px', textAlign: 'right' }}>
                                                            {pct}%
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                        );
                                    })}
                                </tbody>
                            </table>
                        </div>
                    </div>
                )}

                {/* 4 Demographics Cards Grid */}
                <div className="row g-3 mb-4">
                    {/* Jenis Kelamin */}
                    <div className="col-md-6 col-lg-3">
                        <div className="bg-white border rounded-4 shadow-sm h-100 p-3" style={{ borderColor: '#e2e8f0' }}>
                            <h6 className="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                <i className="bi bi-gender-ambiguous text-primary"></i> Jenis Kelamin
                            </h6>
                            {Object.keys(distJk).length > 0 ? (
                                Object.entries(distJk).map(([label, count]) => {
                                    const pct = Math.round((Number(count) / total) * 100);
                                    return (
                                        <div key={label} className="mb-2">
                                            <div className="d-flex justify-content-between mb-1 small">
                                                <span className="fw-medium text-dark">{label}</span>
                                                <span className="fw-bold">{count} <small className="text-muted">({pct}%)</small></span>
                                            </div>
                                            <div className="progress" style={{ height: '6px', borderRadius: '999px', backgroundColor: '#e2e8f0' }}>
                                                <div className={`progress-bar ${label === 'Laki-laki' ? 'bg-primary' : 'bg-danger'} rounded-pill`} style={{ width: `${pct}%` }}></div>
                                            </div>
                                        </div>
                                    );
                                })
                            ) : (
                                <p className="text-muted small mb-0 py-3 text-center">Tidak ada data</p>
                            )}
                        </div>
                    </div>

                    {/* Agama */}
                    <div className="col-md-6 col-lg-3">
                        <div className="bg-white border rounded-4 shadow-sm h-100 p-3" style={{ borderColor: '#e2e8f0' }}>
                            <h6 className="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                <i className="bi bi-moon-stars text-success"></i> Agama
                            </h6>
                            {Object.keys(distAgama).length > 0 ? (
                                Object.entries(distAgama).map(([label, count]) => {
                                    const pct = Math.round((Number(count) / total) * 100);
                                    return (
                                        <div key={label} className="mb-2">
                                            <div className="d-flex justify-content-between mb-1 small">
                                                <span className="fw-medium text-dark">{label}</span>
                                                <span className="fw-bold">{count} <small className="text-muted">({pct}%)</small></span>
                                            </div>
                                            <div className="progress" style={{ height: '6px', borderRadius: '999px', backgroundColor: '#e2e8f0' }}>
                                                <div className="progress-bar bg-success rounded-pill" style={{ width: `${pct}%` }}></div>
                                            </div>
                                        </div>
                                    );
                                })
                            ) : (
                                <p className="text-muted small mb-0 py-3 text-center">Tidak ada data</p>
                            )}
                        </div>
                    </div>

                    {/* Kategori Siswa */}
                    <div className="col-md-6 col-lg-3">
                        <div className="bg-white border rounded-4 shadow-sm h-100 p-3" style={{ borderColor: '#e2e8f0' }}>
                            <h6 className="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                <i className="bi bi-person-badge text-warning"></i> Kategori Siswa
                            </h6>
                            {Object.keys(distKategori).length > 0 ? (
                                Object.entries(distKategori).map(([label, count]) => {
                                    const pct = Math.round((Number(count) / total) * 100);
                                    return (
                                        <div key={label} className="mb-2">
                                            <div className="d-flex justify-content-between mb-1 small">
                                                <span className="fw-medium text-dark">{label}</span>
                                                <span className="fw-bold">{count} <small className="text-muted">({pct}%)</small></span>
                                            </div>
                                            <div className="progress" style={{ height: '6px', borderRadius: '999px', backgroundColor: '#e2e8f0' }}>
                                                <div className="progress-bar bg-warning rounded-pill" style={{ width: `${pct}%` }}></div>
                                            </div>
                                        </div>
                                    );
                                })
                            ) : (
                                <p className="text-muted small mb-0 py-3 text-center">Tidak ada data</p>
                            )}
                        </div>
                    </div>

                    {/* Kategori IPP */}
                    <div className="col-md-6 col-lg-3">
                        <div className="bg-white border rounded-4 shadow-sm h-100 p-3" style={{ borderColor: '#e2e8f0' }}>
                            <h6 className="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                                <i className="bi bi-wallet2 text-info"></i> Kategori IPP
                            </h6>
                            {Object.keys(distIpp).length > 0 ? (
                                Object.entries(distIpp).map(([label, count]) => {
                                    const pct = Math.round((Number(count) / total) * 100);
                                    return (
                                        <div key={label} className="mb-2">
                                            <div className="d-flex justify-content-between mb-1 small">
                                                <span className="fw-medium text-dark">{label}</span>
                                                <span className="fw-bold">{count} <small className="text-muted">({pct}%)</small></span>
                                            </div>
                                            <div className="progress" style={{ height: '6px', borderRadius: '999px', backgroundColor: '#e2e8f0' }}>
                                                <div className="progress-bar bg-info rounded-pill" style={{ width: `${pct}%` }}></div>
                                            </div>
                                        </div>
                                    );
                                })
                            ) : (
                                <p className="text-muted small mb-0 py-3 text-center">Tidak ada data</p>
                            )}
                        </div>
                    </div>
                </div>

                {/* Pekerjaan & Penghasilan Orang Tua with Interactive Tabs */}
                <div className="row g-3 mb-4">
                    {/* Pekerjaan Orang Tua */}
                    <div className="col-md-6">
                        <div className="bg-white border rounded-4 shadow-sm h-100 p-3 p-sm-4" style={{ borderColor: '#e2e8f0' }}>
                            <div className="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                <h6 className="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                    <i className="bi bi-briefcase-fill text-primary"></i> Pekerjaan Orang Tua
                                </h6>
                                <div className="btn-group btn-group-sm" role="group">
                                    <button
                                        type="button"
                                        className={`btn ${jobTab === 'ayah' ? 'btn-primary' : 'btn-outline-secondary'}`}
                                        onClick={() => setJobTab('ayah')}
                                    >
                                        <i className="bi bi-person me-1"></i> Ayah
                                    </button>
                                    <button
                                        type="button"
                                        className={`btn ${jobTab === 'ibu' ? 'btn-primary' : 'btn-outline-secondary'}`}
                                        onClick={() => setJobTab('ibu')}
                                    >
                                        <i className="bi bi-person-heart me-1"></i> Ibu
                                    </button>
                                </div>
                            </div>

                            <div style={{ maxHeight: '360px', overflowY: 'auto' }}>
                                {jobTab === 'ayah' ? (
                                    Object.keys(distPekerjaanAyah).length > 0 ? (
                                        Object.entries(distPekerjaanAyah).map(([label, count]) => {
                                            const pct = Math.round((Number(count) / total) * 100);
                                            return (
                                                <div key={label} className="mb-2">
                                                    <div className="d-flex justify-content-between mb-1 small">
                                                        <span className="text-truncate text-dark fw-medium" style={{ maxWidth: '240px' }}>{label}</span>
                                                        <span className="fw-bold">{count}</span>
                                                    </div>
                                                    <div className="progress" style={{ height: '6px', borderRadius: '999px', backgroundColor: '#e2e8f0' }}>
                                                        <div className="progress-bar bg-primary rounded-pill" style={{ width: `${pct}%` }}></div>
                                                    </div>
                                                </div>
                                            );
                                        })
                                    ) : <p className="text-muted small">Tidak ada data</p>
                                ) : (
                                    Object.keys(distPekerjaanIbu).length > 0 ? (
                                        Object.entries(distPekerjaanIbu).map(([label, count]) => {
                                            const pct = Math.round((Number(count) / total) * 100);
                                            return (
                                                <div key={label} className="mb-2">
                                                    <div className="d-flex justify-content-between mb-1 small">
                                                        <span className="text-truncate text-dark fw-medium" style={{ maxWidth: '240px' }}>{label}</span>
                                                        <span className="fw-bold">{count}</span>
                                                    </div>
                                                    <div className="progress" style={{ height: '6px', borderRadius: '999px', backgroundColor: '#e2e8f0' }}>
                                                        <div className="progress-bar bg-danger rounded-pill" style={{ width: `${pct}%` }}></div>
                                                    </div>
                                                </div>
                                            );
                                        })
                                    ) : <p className="text-muted small">Tidak ada data</p>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Penghasilan Orang Tua */}
                    <div className="col-md-6">
                        <div className="bg-white border rounded-4 shadow-sm h-100 p-3 p-sm-4" style={{ borderColor: '#e2e8f0' }}>
                            <div className="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
                                <h6 className="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                                    <i className="bi bi-cash-stack text-success"></i> Penghasilan Orang Tua
                                </h6>
                                <div className="btn-group btn-group-sm" role="group">
                                    <button
                                        type="button"
                                        className={`btn ${incomeTab === 'ayah' ? 'btn-success' : 'btn-outline-secondary'}`}
                                        onClick={() => setIncomeTab('ayah')}
                                    >
                                        <i className="bi bi-person me-1"></i> Ayah
                                    </button>
                                    <button
                                        type="button"
                                        className={`btn ${incomeTab === 'ibu' ? 'btn-success' : 'btn-outline-secondary'}`}
                                        onClick={() => setIncomeTab('ibu')}
                                    >
                                        <i className="bi bi-person-heart me-1"></i> Ibu
                                    </button>
                                </div>
                            </div>

                            <div style={{ maxHeight: '360px', overflowY: 'auto' }}>
                                {incomeTab === 'ayah' ? (
                                    Object.keys(distPenghasilanAyah).length > 0 ? (
                                        Object.entries(distPenghasilanAyah).map(([label, count]) => {
                                            const pct = Math.round((Number(count) / total) * 100);
                                            return (
                                                <div key={label} className="mb-2">
                                                    <div className="d-flex justify-content-between mb-1 small">
                                                        <span className="text-truncate text-dark fw-medium" style={{ maxWidth: '240px' }}>{label}</span>
                                                        <span className="fw-bold">{count}</span>
                                                    </div>
                                                    <div className="progress" style={{ height: '6px', borderRadius: '999px', backgroundColor: '#e2e8f0' }}>
                                                        <div className="progress-bar bg-success rounded-pill" style={{ width: `${pct}%` }}></div>
                                                    </div>
                                                </div>
                                            );
                                        })
                                    ) : <p className="text-muted small">Tidak ada data</p>
                                ) : (
                                    Object.keys(distPenghasilanIbu).length > 0 ? (
                                        Object.entries(distPenghasilanIbu).map(([label, count]) => {
                                            const pct = Math.round((Number(count) / total) * 100);
                                            return (
                                                <div key={label} className="mb-2">
                                                    <div className="d-flex justify-content-between mb-1 small">
                                                        <span className="text-truncate text-dark fw-medium" style={{ maxWidth: '240px' }}>{label}</span>
                                                        <span className="fw-bold">{count}</span>
                                                    </div>
                                                    <div className="progress" style={{ height: '6px', borderRadius: '999px', backgroundColor: '#e2e8f0' }}>
                                                        <div className="progress-bar bg-danger rounded-pill" style={{ width: `${pct}%` }}></div>
                                                    </div>
                                                </div>
                                            );
                                        })
                                    ) : <p className="text-muted small">Tidak ada data</p>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Tanggungan Bersekolah Per Siswa & Per KK */}
                <div className="row g-3 mb-4">
                    {/* Per Siswa */}
                    <div className="col-md-6">
                        <div className="bg-white border rounded-4 shadow-sm h-100 p-3 p-sm-4" style={{ borderColor: '#e2e8f0' }}>
                            <h6 className="fw-bold text-dark mb-1">Jumlah Tanggungan Bersekolah (Per Siswa)</h6>
                            <p className="small text-muted mb-3">
                                Berdasarkan data <em>"Jumlah tanggungan anak yang bersekolah di SMAN Benlutu"</em> yang diisi pada profil masing-masing siswa.
                            </p>
                            {Object.keys(distTanggunganSiswa).length > 0 ? (
                                Object.entries(distTanggunganSiswa).map(([label, count]) => {
                                    const pct = Math.round((Number(count) / total) * 100);
                                    return (
                                        <div key={label} className="mb-2">
                                            <div className="d-flex justify-content-between mb-1 small">
                                                <span className="fw-medium text-dark">{label || '0'} Anak</span>
                                                <span className="fw-bold">{count} Siswa <small className="text-muted">({pct}%)</small></span>
                                            </div>
                                            <div className="progress" style={{ height: '6px', borderRadius: '999px', backgroundColor: '#e2e8f0' }}>
                                                <div className="progress-bar bg-primary rounded-pill" style={{ width: `${pct}%` }}></div>
                                            </div>
                                        </div>
                                    );
                                })
                            ) : (
                                <p className="text-muted small mb-0 py-3 text-center">Tidak ada data</p>
                            )}
                        </div>
                    </div>

                    {/* Per Kepala Keluarga */}
                    <div className="col-md-6">
                        <div className="bg-white border rounded-4 shadow-sm h-100 p-3 p-sm-4" style={{ borderColor: '#e2e8f0' }}>
                            <h6 className="fw-bold text-dark mb-1">Jumlah Tanggungan Bersekolah (Per Kepala Keluarga)</h6>
                            <p className="small text-muted mb-3">
                                Dihitung berdasarkan pengelompokan orang tua/wali sama <em>(Proxy Kepala Keluarga)</em>.
                            </p>
                            {Object.keys(distTanggunganKeluarga).length > 0 ? (
                                Object.entries(distTanggunganKeluarga).map(([label, count]) => {
                                    const pct = Math.round((Number(count) / totalFamiliesCount) * 100);
                                    return (
                                        <div key={label} className="mb-2">
                                            <div className="d-flex justify-content-between mb-1 small">
                                                <span className="fw-medium text-dark">{label || '0'} Anak</span>
                                                <span className="fw-bold">{count} KK <small className="text-muted">({pct}%)</small></span>
                                            </div>
                                            <div className="progress" style={{ height: '6px', borderRadius: '999px', backgroundColor: '#e2e8f0' }}>
                                                <div className="progress-bar bg-success rounded-pill" style={{ width: `${pct}%` }}></div>
                                            </div>
                                        </div>
                                    );
                                })
                            ) : (
                                <p className="text-muted small mb-0 py-3 text-center">Tidak ada data</p>
                            )}
                        </div>
                    </div>
                </div>

                {/* Daftar Rincian Tanggungan Per Keluarga (Dengan Live Search React) */}
                <div className="bg-white border rounded-4 shadow-sm mb-4" style={{ borderColor: '#e2e8f0', overflow: 'hidden' }}>
                    <div className="px-4 py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5 className="fw-bold mb-0 text-dark d-flex align-items-center gap-2" style={{ fontSize: '1rem' }}>
                            <i className="bi bi-people-fill text-primary"></i> Daftar Rincian Tanggungan Per Keluarga
                        </h5>
                        <div className="d-flex align-items-center gap-2">
                            <div className="input-group input-group-sm" style={{ maxWidth: '280px' }}>
                                <span className="input-group-text bg-light border-end-0"><i className="bi bi-search"></i></span>
                                <input
                                    type="text"
                                    className="form-control border-start-0 ps-1"
                                    placeholder="Cari kepala keluarga / siswa..."
                                    value={searchQuery}
                                    onChange={(e) => setSearchQuery(e.target.value)}
                                />
                            </div>
                            <span className="badge bg-light text-secondary border px-3 py-2 rounded-pill small">
                                {filteredFamilies.length} KK Terdata
                            </span>
                        </div>
                    </div>
                    <div className="table-responsive" style={{ maxHeight: '520px', overflowY: 'auto' }}>
                        <table className="table table-hover align-middle mb-0" style={{ fontSize: '0.88rem' }}>
                            <thead className="table-light sticky-top">
                                <tr>
                                    <th className="ps-4" style={{ width: '280px' }}>Kepala Keluarga (Ayah/Wali)</th>
                                    <th className="text-center" style={{ width: '160px' }}>Jml Tanggungan</th>
                                    <th className="pe-4">Siswa (Anak yang Bersekolah)</th>
                                </tr>
                            </thead>
                            <tbody>
                                {filteredFamilies.length > 0 ? (
                                    filteredFamilies.map((f, idx) => (
                                        <tr key={idx}>
                                            <td className="ps-4 fw-bold text-dark">
                                                <div className="d-flex align-items-center gap-2">
                                                    <div className="rounded-circle bg-light border d-flex align-items-center justify-content-center text-primary" style={{ width: '32px', height: '32px', fontSize: '0.85rem' }}>
                                                        <i className="bi bi-person"></i>
                                                    </div>
                                                    <span>{f.kepala_keluarga}</span>
                                                </div>
                                            </td>
                                            <td className="text-center">
                                                <span className={`badge ${f.tanggungan > 0 ? 'bg-primary-subtle text-primary border border-primary-subtle' : 'bg-light text-secondary border'} px-3 py-2 rounded-pill`}>
                                                    {f.tanggungan} Anak
                                                </span>
                                            </td>
                                            <td className="pe-4">
                                                <div className="d-flex flex-wrap gap-1">
                                                    {(f.siswa_list || []).map((sname, sIdx) => (
                                                        <span key={sIdx} className="badge bg-light text-dark border px-2 py-1 rounded-2 fw-normal d-inline-flex align-items-center gap-1">
                                                            <i className="bi bi-mortarboard-fill text-primary"></i> {sname}
                                                        </span>
                                                    ))}
                                                </div>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan="3" className="text-center text-muted py-5">
                                            <i className="bi bi-folder-x fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                            Tidak ada data keluarga yang sesuai dengan pencarian.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>

            {/* Footer */}
            <footer className="bg-white border-top py-4 text-center text-muted small mt-auto">
                <div className="container-fluid">
                    <div dangerouslySetInnerHTML={{ __html: copyright }} />
                    <div className="text-secondary opacity-75 mt-1" style={{ fontSize: '0.76rem' }}>
                        Sistem Informasi Iuran Pengembangan Pendidikan Terintegrasi &bull; {schoolName}
                    </div>
                </div>
            </footer>
        </div>
    );
}
