# CANDY AREA 105 PAGE QUEUE
- Parent / Owner: `CANDY_AREA_STAFF_PRODUCTION_RUNBOOK.md`
- Scope: Fixed 105-target area cohort and production order
- Lifecycle: Active
- Source of Truth Responsibility: Canonical fixed area queue order; not current eligibility
- Related Documents: `generated/CANDY_UPCOMING_PAGES.md` and the area production runbook
- Related Implementation Files: `Text_area_data/` targets identified by the queue

- Updated: 2026-08-08
- Purpose: Preserve the fixed 105-target cohort and its production order

## 1. Cohort Provenance

This queue was selected from the 167-input snapshot reconciled on 2026-07-20.
The numbers below explain only how this fixed 105-target cohort was formed.
They are not the current `Text_area_data` population, image inventory, page
population, or eligibility result.

| Classification | Count |
|---|---:|
| Excluded from new production because source HTML exists | 57 |
| No source HTML; information file and two correctly named slug images exist | 105 |
| └ Normal new candidate with all three page files absent | 105 |
| └ Existing inconsistency with public PHP and dataset PHP present but source HTML absent | 0 |
| No source HTML; information file exists but correctly named slug images are missing | 5 |
| Total | 167 |

Membership in this cohort does not prove current eligibility. For each target,
the current gate and generated current-state documents selected by root
`AGENTS.md` through `codex/WORK_ROUTING.md` determine whether production may proceed.

## 2. Operating Rules

- Process two targets in the first batch, then five per batch after review.
- Work from the top. The dedicated gate skips an ineligible `READY_CANDIDATE` during selection and chooses the first row that returns `NEW_PAGE_TARGET_OK=<slug>`; record any explicit user-directed order change.
- Use one row per slug and do not create a separate history table.
- After build, set the target row to `LOCAL_COMPLETE` or `IN_PROGRESS`.

Status values: `READY_CANDIDATE / IN_PROGRESS / LOCAL_COMPLETE / COMMITTED / PUSHED / PUBLISHED / BLOCKED`

## 3. Production Candidates: 105

| No. | Region name | Slug | Status | Record |
|---:|---|---|---|---|
| 1 | 花尾町 | `hanaomachi` | PUBLISHED | Codex / 2026-07-14 / Commit `44df27b` / Actions `29289499915` / Production HTTP and browser verified |
| 2 | 皆与志町 | `minayoshicho` | PUBLISHED | Codex / 2026-07-14 / Commit `f1ba7fd` / Actions `29294348852` / Production HTTP verified |
| 3 | 吉野 | `yoshino` | PUBLISHED | Codex / 2026-07-14 / Commit `f1ba7fd` / Actions `29294348852` / Production HTTP verified |
| 4 | 吉野町 | `yoshinocho` | PUBLISHED | Codex / 2026-07-14 / Commit `98b009d` / Actions `29295020132` / Production HTTP verified |
| 5 | 宮之浦町 | `miyanouracho` | BLOCKED | Slug conflict: the area index uses `miyanouramachi`; Text canonical is `miyanouracho`. Awaiting a decision without automatic replacement |
| 6 | 玉里団地 | `tamazatodanchi` | PUBLISHED | Codex / 2026-07-14 / Commit `60fa1ab` / Actions `29300812695` / Production HTTP verified |
| 7 | 玉里町 | `tamazatocho` | PUBLISHED | Codex / 2026-07-14 / Commit `80eb495` / Actions `29301384229` / Production HTTP verified |
| 8 | 原良 | `harara` | PUBLISHED | Codex / 2026-07-14 / Commit `edc27df` / Actions `29301447744` / Production HTTP verified |
| 9 | 光山 | `hikariyama` | PUBLISHED | Codex / 2026-07-14 / Commit `03ba6e6` / Actions `29301654365` / Production HTTP verified |
| 10 | 広木 | `hiroki` | PUBLISHED | Codex / 2026-07-14 / Commit `1620c16` / Actions `29301707302` / Production HTTP verified |
| 11 | 山下町 | `yamashitacho` | PUBLISHED | Codex / 2026-07-14 / Commit `2a8a9c4` / Actions `29301766001` / Production HTTP verified |
| 12 | 山田町 | `yamadacho` | IN_PROGRESS | Dedicated tool / 2026-07-14 / Three files, shared registration, and static validation complete / PHP CLI unverified |
| 13 | 山之口町 | `yamanokuchicho` | IN_PROGRESS | Dedicated tool / 2026-07-14 / Three files, shared registration, and static validation complete / PHP CLI unverified |
| 14 | 四元町 | `yotsumotocho` | IN_PROGRESS | Dedicated tool / 2026-07-15 / Three files, shared registration, and static validation complete / PHP CLI unverified |
| 15 | 紫原 | `murasakibaru` | LOCAL_COMPLETE | Dedicated tool / 2026-07-24 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 16 | 慈眼寺町 | `jigenjicho` | IN_PROGRESS | Dedicated tool / 2026-07-18 / Three files, shared registration, and static validation complete / PHP CLI unverified |
| 17 | 自由ヶ丘 | `jiyugaoka` | IN_PROGRESS | Dedicated tool / 2026-07-18 / Three files, shared registration, and static validation complete / PHP CLI unverified |
| 18 | 七ツ島 | `nanatsujima` | IN_PROGRESS | Dedicated tool / 2026-07-16 / Three files, shared registration, and static validation complete / PHP CLI unverified |
| 19 | 若葉町 | `wakabacho` | IN_PROGRESS | Dedicated tool / 2026-07-18 / Three files, shared registration, and static validation complete / PHP CLI unverified |
| 20 | 住吉町 | `sumiyoshicho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-24 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 21 | 春山町 | `haruyamacho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-24 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 22 | 小松原 | `komatsubara` | BLOCKED | Broken partial files removed on 2026-07-20; area-index registration is missing |
| 23 | 松原町 | `matsubaracho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-24 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 24 | 照国町 | `terukunicho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-24 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 25 | 上谷口町 | `kamitaniguchicho` | BLOCKED | Broken partial files removed on 2026-07-20; area-index registration is missing |
| 26 | 上福元町 | `kamifukumotocho` | BLOCKED | Broken partial files removed on 2026-07-20; area-index registration is missing |
| 27 | 上本町 | `kamihonmachi` | BLOCKED | Broken partial files removed on 2026-07-20; area-index registration is missing |
| 28 | 上竜尾町 | `kamitatsuocho` | BLOCKED | Broken partial files removed on 2026-07-20; area-index registration is missing |
| 29 | 城山 | `shiroyama` | LOCAL_COMPLETE | Dedicated tool / 2026-07-24 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 30 | 城山町 | `shiroyamacho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-25 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 31 | 城西 | `josei` | LOCAL_COMPLETE | Dedicated tool / 2026-07-25 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 32 | 常盤 | `tokiwa` | LOCAL_COMPLETE | Dedicated tool / 2026-07-25 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 33 | 新栄町 | `shineicho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-25 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 34 | 新照院町 | `shinshoincho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-25 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 35 | 新町 | `shimmachi` | LOCAL_COMPLETE | Dedicated tool / 2026-07-28 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 36 | 真砂町 | `masagocho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-28 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 37 | 真砂本町 | `masagohonmachi` | READY_CANDIDATE | |
| 38 | 星ヶ峯 | `hoshigamine` | LOCAL_COMPLETE | Dedicated tool / 2026-07-28 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 39 | 清水町 | `shimizucho` | BLOCKED | Broken partial files removed on 2026-07-20; area-index registration is missing |
| 40 | 清和 | `seiwa` | LOCAL_COMPLETE | Dedicated tool / 2026-07-28 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 41 | 西伊敷 | `nishiishiki` | LOCAL_COMPLETE | Dedicated tool / 2026-07-28 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 42 | 西佐多町 | `nishisatacho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-29 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 43 | 西坂元町 | `nishisakamotocho` | LOCAL_COMPLETE | Dedicated tool / 2026-08-06 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 44 | 西紫原町 | `nishimurasakibarucho` | LOCAL_COMPLETE | Dedicated tool / 2026-08-06 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 45 | 西千石町 | `nishisengokucho` | LOCAL_COMPLETE | Dedicated tool / 2026-08-06 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 46 | 西谷山 | `nishitaniyama` | LOCAL_COMPLETE | Dedicated tool / 2026-08-06 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 47 | 西田 | `nishida` | LOCAL_COMPLETE | Dedicated tool / 2026-08-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 48 | 西別府町 | `nishibeppucho` | LOCAL_COMPLETE | Dedicated tool / 2026-08-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 49 | 西俣町 | `nishimatacho` | LOCAL_COMPLETE | Dedicated tool / 2026-08-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 50 | 千日町 | `sennichicho` | READY_CANDIDATE | |
| 51 | 川上町 | `kawakamicho` | BLOCKED | Broken partial files removed on 2026-07-20; area-index registration is missing |
| 52 | 川田町 | `kawadacho` | BLOCKED | Broken partial files removed on 2026-07-20; area-index registration is missing |
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
| 89 | 平川町 | `hirakawacho` | BLOCKED | Broken partial files removed on 2026-07-20; area-index registration is missing |
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

Current image availability, artifact consistency, and eligibility are not
stored as queue-wide counts here. Use each row's status as workflow state, then
revalidate the selected target against actual files and the generated
current-state documents before production.
