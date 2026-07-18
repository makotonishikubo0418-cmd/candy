# CANDY AREA 105 PAGE QUEUE

- Updated: 2026-07-13
- Purpose: Control production order and prevent duplication for unbuilt area pages

## 1. Population Basis

The 167 canonical inputs under `Text_area_data`, excluding Backup, were reconciled with actual files and public images.

| Classification | Count |
|---|---:|
| Excluded from new production because source HTML exists | 57 |
| No source HTML; information file and two correctly named slug images exist | 105 |
| └ Normal new candidate with all three page files absent | 96 |
| └ Existing inconsistency with public PHP and dataset PHP present but source HTML absent | 9 |
| No source HTML; information file exists but correctly named slug images are missing | 5 |
| Total | 167 |

The 105 candidates are not guaranteed to have complete input content. The 96 normal new candidates are `READY_CANDIDATE` inputs eligible for pre-production review. Keep the nine existing inconsistencies separate from normal new production; inspect their affected scope as existing-page repairs and act only after user approval. Read each text file in full for every batch and change the status to `BLOCKED` when required content is missing.

## 2. Operating Rules

- Process two targets in the first batch, then five per batch after review.
- Work from the top. The dedicated gate skips an ineligible `READY_CANDIDATE` during selection and chooses the first row that returns `NEW_PAGE_TARGET_OK=<slug>`; record any explicit user-directed order change.
- Use one row per slug and do not create a separate history table.
- After build, set the target row to `LOCAL_COMPLETE` or `IN_PROGRESS`.
- Do not create a later Commit or Push solely to record publication results.
- Verify publication state through the GitHub Commit, Actions, and production HTTP.
- Do not process separate batches concurrently across multiple Codex tasks.

Status values: `READY_CANDIDATE / IN_PROGRESS / LOCAL_COMPLETE / COMMITTED / PUSHED / PUBLISHED / BLOCKED`

## 3. Production Candidates: 105

| No. | Region name | Slug | Status | Record |
|---:|---|---|---|---|
| 1 | 花尾町 | `hanaomachi` | PUBLISHED | Codex / 2026-07-14 / Commit `44df27b` / Actions `29289499915` / Production HTTP and browser verified |
| 2 | 皆与志町 | `minayoshicho` | PUBLISHED | Codex / 2026-07-14 / Commit `f1ba7fd` / Actions `29294348852` / Production HTTP verified |
| 3 | 吉野 | `yoshino` | PUBLISHED | Codex / 2026-07-14 / Commit `f1ba7fd` / Actions `29294348852` / Production HTTP verified |
| 4 | 吉野町 | `yoshinocho` | PUBLISHED | Codex / 2026-07-14 / Commit `98b009d` / Actions `29295020132` / Production HTTP verified |
| 5 | 宮之浦町 | `miyanouracho` | BLOCKED_SLUG_CONFLICT | The area index uses `miyanouramachi`; Text canonical is `miyanouracho`. Awaiting a decision without automatic replacement |
| 6 | 玉里団地 | `tamazatodanchi` | PUBLISHED | Codex / 2026-07-14 / Commit `60fa1ab` / Actions `29300812695` / Production HTTP verified |
| 7 | 玉里町 | `tamazatocho` | PUBLISHED | Codex / 2026-07-14 / Commit `80eb495` / Actions `29301384229` / Production HTTP verified |
| 8 | 原良 | `harara` | PUBLISHED | Codex / 2026-07-14 / Commit `edc27df` / Actions `29301447744` / Production HTTP verified |
| 9 | 光山 | `hikariyama` | PUBLISHED | Codex / 2026-07-14 / Commit `03ba6e6` / Actions `29301654365` / Production HTTP verified |
| 10 | 広木 | `hiroki` | PUBLISHED | Codex / 2026-07-14 / Commit `1620c16` / Actions `29301707302` / Production HTTP verified |
| 11 | 山下町 | `yamashitacho` | PUBLISHED | Codex / 2026-07-14 / Commit `2a8a9c4` / Actions `29301766001` / Production HTTP verified |
| 12 | 山田町 | `yamadacho` | IN_PROGRESS | Dedicated tool / 2026-07-14 / Three files, shared registration, and static validation complete / PHP CLI unverified |
| 13 | 山之口町 | `yamanokuchicho` | IN_PROGRESS | Dedicated tool / 2026-07-14 / Three files, shared registration, and static validation complete / PHP CLI unverified |
| 14 | 四元町 | `yotsumotocho` | IN_PROGRESS | Dedicated tool / 2026-07-15 / Three files, shared registration, and static validation complete / PHP CLI unverified |
| 15 | 紫原 | `murasakibaru` | READY_CANDIDATE | |
| 16 | 慈眼寺町 | `jigenjicho` | IN_PROGRESS | 専用ツール / 2026-07-18 / 3ファイル・共有登録・静的検査済み / PHP CLI未確認 |
| 17 | 自由ヶ丘 | `jiyugaoka` | IN_PROGRESS | 専用ツール / 2026-07-18 / 3ファイル・共有登録・静的検査済み / PHP CLI未確認 |
| 18 | 七ツ島 | `nanatsujima` | IN_PROGRESS | Dedicated tool / 2026-07-16 / Three files, shared registration, and static validation complete / PHP CLI unverified |
| 19 | 若葉町 | `wakabacho` | IN_PROGRESS | 専用ツール / 2026-07-18 / 3ファイル・共有登録・静的検査済み / PHP CLI未確認 |
| 20 | 住吉町 | `sumiyoshicho` | READY_CANDIDATE | |
| 21 | 春山町 | `haruyamacho` | READY_CANDIDATE | |
| 22 | 小松原 | `komatsubara` | BLOCKED_EXISTING_PARTIAL | Public PHP and dataset exist; source HTML is missing |
| 23 | 松原町 | `matsubaracho` | READY_CANDIDATE | |
| 24 | 照国町 | `terukunicho` | READY_CANDIDATE | |
| 25 | 上谷口町 | `kamitaniguchicho` | BLOCKED_EXISTING_PARTIAL | Public PHP and dataset exist; source HTML is missing |
| 26 | 上福元町 | `kamifukumotocho` | BLOCKED_EXISTING_PARTIAL | Public PHP and dataset exist; source HTML is missing |
| 27 | 上本町 | `kamihonmachi` | BLOCKED_EXISTING_PARTIAL | Public PHP and dataset exist; source HTML is missing |
| 28 | 上竜尾町 | `kamitatsuocho` | BLOCKED_EXISTING_PARTIAL | Public PHP and dataset exist; source HTML is missing |
| 29 | 城山 | `shiroyama` | READY_CANDIDATE | |
| 30 | 城山町 | `shiroyamacho` | READY_CANDIDATE | |
| 31 | 城西 | `josei` | READY_CANDIDATE | |
| 32 | 常盤 | `tokiwa` | READY_CANDIDATE | |
| 33 | 新栄町 | `shineicho` | READY_CANDIDATE | |
| 34 | 新照院町 | `shinshoincho` | READY_CANDIDATE | |
| 35 | 新町 | `shimmachi` | READY_CANDIDATE | |
| 36 | 真砂町 | `masagocho` | READY_CANDIDATE | |
| 37 | 真砂本町 | `masagohonmachi` | READY_CANDIDATE | |
| 38 | 星ヶ峯 | `hoshigamine` | READY_CANDIDATE | |
| 39 | 清水町 | `shimizucho` | BLOCKED_EXISTING_PARTIAL | Public PHP and dataset exist; source HTML is missing |
| 40 | 清和 | `seiwa` | READY_CANDIDATE | |
| 41 | 西伊敷 | `nishiishiki` | READY_CANDIDATE | |
| 42 | 西佐多町 | `nishisatacho` | READY_CANDIDATE | |
| 43 | 西坂元町 | `nishisakamotocho` | READY_CANDIDATE | |
| 44 | 西紫原町 | `nishimurasakibarucho` | READY_CANDIDATE | |
| 45 | 西千石町 | `nishisengokucho` | READY_CANDIDATE | |
| 46 | 西谷山 | `nishitaniyama` | READY_CANDIDATE | |
| 47 | 西田 | `nishida` | READY_CANDIDATE | |
| 48 | 西別府町 | `nishibeppucho` | READY_CANDIDATE | |
| 49 | 西俣町 | `nishimatacho` | READY_CANDIDATE | |
| 50 | 千日町 | `sennichicho` | READY_CANDIDATE | |
| 51 | 川上町 | `kawakamicho` | BLOCKED_EXISTING_PARTIAL | Public PHP and dataset exist; source HTML is missing |
| 52 | 川田町 | `kawadacho` | BLOCKED_EXISTING_PARTIAL | Public PHP and dataset exist; source HTML is missing |
| 53 | 船津町 | `funatsucho` | READY_CANDIDATE | |
| 54 | 草牟田 | `soumuta` | READY_CANDIDATE | |
| 55 | 草牟田町 | `soumutacho` | READY_CANDIDATE | |
| 56 | 大黒町 | `daikokucho` | READY_CANDIDATE | |
| 57 | 大明丘 | `daimyogaoka` | READY_CANDIDATE | |
| 58 | 鷹師 | `takashi` | READY_CANDIDATE | |
| 59 | 谷山港 | `taniyamakou` | READY_CANDIDATE | |
| 60 | 谷山中央 | `taniyamachuuou` | READY_CANDIDATE | |
| 61 | 中央港新町 | `chuokoshinmachi` | IN_PROGRESS | Dedicated tool / 2026-07-16 / Three files, shared registration, and static validation complete / PHP CLI unverified |
| 62 | 中央町 | `chuocho` | READY_CANDIDATE | |
| 63 | 中山 | `chuzan` | READY_CANDIDATE | |
| 64 | 中山町 | `chuzancho` | READY_CANDIDATE | |
| 65 | 中町 | `nakamachi` | READY_CANDIDATE | |
| 66 | 長田町 | `nagatacho` | READY_CANDIDATE | |
| 67 | 直木町 | `naokicho` | READY_CANDIDATE | |
| 68 | 田上 | `tagami` | READY_CANDIDATE | |
| 69 | 田上台 | `tagamidai` | READY_CANDIDATE | |
| 70 | 田上町 | `tagamicho` | READY_CANDIDATE | |
| 71 | 唐湊 | `toso` | READY_CANDIDATE | |
| 72 | 東開町 | `tokaicho` | READY_CANDIDATE | |
| 73 | 東郡元町 | `higashikoorimotocho` | READY_CANDIDATE | |
| 74 | 東佐多町 | `higashisatacho` | READY_CANDIDATE | |
| 75 | 東坂元 | `higashisakamoto` | READY_CANDIDATE | |
| 76 | 東千石町 | `higashisengokucho` | READY_CANDIDATE | |
| 77 | 東谷山 | `higashitaniyama` | READY_CANDIDATE | |
| 78 | 東俣町 | `higashimatacho` | READY_CANDIDATE | |
| 79 | 南栄 | `nanei` | READY_CANDIDATE | |
| 80 | 南郡元町 | `minamikorimotocho` | READY_CANDIDATE | |
| 81 | 南新町 | `minamishinmachi` | READY_CANDIDATE | |
| 82 | 南林寺町 | `nanrinjicho` | READY_CANDIDATE | |
| 83 | 日之出町 | `hinodecho` | READY_CANDIDATE | |
| 84 | 樋之口町 | `tenokuchicho` | READY_CANDIDATE | |
| 85 | 浜町 | `hamamachi` | READY_CANDIDATE | |
| 86 | 武 | `take` | READY_CANDIDATE | |
| 87 | 武岡 | `takeoka` | READY_CANDIDATE | |
| 88 | 福山町 | `fukuyamacho` | READY_CANDIDATE | |
| 89 | 平川町 | `hirakawacho` | BLOCKED_EXISTING_PARTIAL | Public PHP and dataset exist; source HTML is missing |
| 90 | 平田町 | `hiratacho` | READY_CANDIDATE | |
| 91 | 平之町 | `hiranocho` | READY_CANDIDATE | |
| 92 | 堀江町 | `horiecho` | READY_CANDIDATE | |
| 93 | 本港新町 | `honkoshinmachi` | READY_CANDIDATE | |
| 94 | 本城町 | `honjocho` | READY_CANDIDATE | |
| 95 | 本名町 | `honmyocho` | READY_CANDIDATE | |
| 96 | 牟礼岡 | `muregaoka` | READY_CANDIDATE | |
| 97 | 名山町 | `meizancho` | READY_CANDIDATE | |
| 98 | 明和 | `meiwa` | READY_CANDIDATE | |
| 99 | 柳町 | `yanagimachi` | READY_CANDIDATE | |
| 100 | 油須木町 | `yusukicho` | READY_CANDIDATE | |
| 101 | 与次郎 | `yojiro` | READY_CANDIDATE | |
| 102 | 緑ヶ丘町 | `midorigaokacho` | READY_CANDIDATE | |
| 103 | 冷水町 | `hiyamizucho` | READY_CANDIDATE | |
| 104 | 和田 | `wada` | READY_CANDIDATE | |
| 105 | 皷川町 | `tsuzugawacho` | READY_CANDIDATE | |

## 4. Blocked by Missing Images: 0

Current accepted and public files contain complete canonical `_1` and `_2`
pairs for every candidate previously listed in this section. Similar-slug legacy
images remain separate and MUST NOT be renamed or substituted automatically.

Image availability alone does not make a candidate publishable. Continue to
apply the area-index, page-structure, input, and publication gates.

## 5. Blocked by Existing Inconsistencies: 9

These candidates already have public PHP and page-specific dataset PHP, but source HTML is missing. Treat them as repairs of existing inconsistencies, not as normal new generation.

```text
kamihonmachi, kamifukumotocho, kamitatsuocho,
kamitaniguchicho, komatsubara, kawakamicho,
kawadacho, hirakawacho, shimizucho
```

Do not overwrite existing PHP or datasets. Review content, dataset_base registration, the area index, sitemap, and legacy slug individually, then obtain user approval.
