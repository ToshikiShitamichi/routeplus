// frontend/src/components/DonutChart/index.jsx
// ライブラリ不要・SVGで描画

export default function DonutChart({ done, total }) {
    const percent = total > 0 ? Math.round((done / total) * 100) : 0;

    // SVGの計算
    const radius = 36;
    const circumference = 2 * Math.PI * radius; // 円周
    const offset = circumference - (percent / 100) * circumference; // 未達成部分

    return (
        <div className="donut-wrap">
            <svg width="88" height="88" viewBox="0 0 88 88">
                {/* 背景の円 */}
                <circle
                    cx="44" cy="44" r={radius}
                    fill="none"
                    stroke="#e9ecef"
                    strokeWidth="10"
                />
                {/* 進捗の円（上から時計回りに描画） */}
                <circle
                    cx="44" cy="44" r={radius}
                    fill="none"
                    stroke="#4f46e5"
                    strokeWidth="10"
                    strokeLinecap="round"
                    strokeDasharray={circumference}
                    strokeDashoffset={offset}
                    transform="rotate(-90 44 44)" // 12時スタート
                    style={{ transition: "stroke-dashoffset 0.6s ease" }}
                />
                {/* 中央テキスト */}
                <text x="44" y="40" textAnchor="middle" fontSize="14" fontWeight="700" fill="#1a1a2e">
                    {percent}%
                </text>
                <text x="44" y="56" textAnchor="middle" fontSize="10" fill="#888">
                    {done}/{total}
                </text>
            </svg>
        </div>
    );
}