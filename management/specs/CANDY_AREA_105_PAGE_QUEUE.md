# CANDY AREA 105 PAGE QUEUE

- Updated: 2026-10-09
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
`AGENTS.md` through `management/INDEX.md` determine whether production may proceed.

## 2. Operating Rules

- Process two targets in the first batch, then five per batch after review.
- Work from the top. The dedicated gate skips an ineligible `READY_CANDIDATE` during selection and chooses the first row that returns `NEW_PAGE_TARGET_OK=<slug>`; record any explicit user-directed order change.
- Use one row per slug and do not create a separate history table.
- After build, set the target row to `LOCAL_COMPLETE` or `IN_PROGRESS`.

Status values: `READY_CANDIDATE / IN_PROGRESS / LOCAL_COMPLETE / COMMITTED / PUSHED / PUBLISHED / BLOCKED`

## 3. Production Candidates: 105

| No. | Region name | Slug | Status | Record |
|---:|---|---|---|---|
| 1 | 花尾町 | `hanaomachi` | PUBLISHED | Codex / 2026-07-14 / Commit `44df27b` / Actions `29289499915` / Production HTTP and browser verified / Local Sチャンネル fee corrected to source Text on 2026-10-09; Git and production publication not yet performed |
| 2 | 皆与志町 | `minayoshicho` | PUBLISHED | Codex / 2026-07-14 / Commit `f1ba7fd` / Actions `29294348852` / Production HTTP verified |
| 3 | 吉野 | `yoshino` | PUBLISHED | Codex / 2026-07-14 / Commit `f1ba7fd` / Actions `29294348852` / Production HTTP verified |
| 4 | 吉野町 | `yoshinocho` | PUBLISHED | Codex / 2026-07-14 / Commit `98b009d` / Actions `29295020132` / Production HTTP verified |
| 5 | 宮之浦町 | `miyanouracho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 6 | 玉里団地 | `tamazatodanchi` | PUBLISHED | Codex / 2026-07-14 / Commit `60fa1ab` / Actions `29300812695` / Production HTTP verified |
| 7 | 玉里町 | `tamazatocho` | PUBLISHED | Codex / 2026-07-14 / Commit `80eb495` / Actions `29301384229` / Production HTTP verified |
| 8 | 原良 | `harara` | PUBLISHED | Codex / 2026-07-14 / Commit `edc27df` / Actions `29301447744` / Production HTTP verified |
| 9 | 光山 | `hikariyama` | PUBLISHED | Codex / 2026-07-14 / Commit `03ba6e6` / Actions `29301654365` / Production HTTP verified |
| 10 | 広木 | `hiroki` | PUBLISHED | Codex / 2026-07-14 / Commit `1620c16` / Actions `29301707302` / Production HTTP verified |
| 11 | 山下町 | `yamashitacho` | PUBLISHED | Codex / 2026-07-14 / Commit `2a8a9c4` / Actions `29301766001` / Production HTTP verified |
| 12 | 山田町 | `yamadacho` | LOCAL_COMPLETE | Status correction / 2026-10-09 / Three files and shared registration complete / Existing audit passed / PHP syntax verified |
| 13 | 山之口町 | `yamanokuchicho` | LOCAL_COMPLETE | Status correction / 2026-10-09 / Three files and shared registration complete / Existing audit passed / PHP syntax verified |
| 14 | 四元町 | `yotsumotocho` | LOCAL_COMPLETE | Status correction / 2026-10-09 / Three files and shared registration complete / PHP syntax verified / Current source Text is missing or unidentifiable / User confirmed on 2026-10-09 that the existing page must remain and this state is acceptable |
| 15 | 紫原 | `murasakibaru` | LOCAL_COMPLETE | Dedicated tool / 2026-07-24 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 16 | 慈眼寺町 | `jigenjicho` | LOCAL_COMPLETE | Status correction / 2026-10-09 / Three files and shared registration complete / Existing audit passed / PHP syntax verified |
| 17 | 自由ヶ丘 | `jiyugaoka` | LOCAL_COMPLETE | Status correction / 2026-10-09 / Three files and shared registration complete / Existing audit passed / PHP syntax verified |
| 18 | 七ツ島 | `nanatsujima` | LOCAL_COMPLETE | Status correction / 2026-10-09 / Three files and shared registration complete / PHP syntax verified / Current source Text is missing or unidentifiable / User confirmed on 2026-10-09 that the existing page must remain and this state is acceptable |
| 19 | 若葉町 | `wakabacho` | LOCAL_COMPLETE | Status correction / 2026-10-09 / Three files and shared registration complete / Existing audit passed / PHP syntax verified |
| 20 | 住吉町 | `sumiyoshicho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-24 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 21 | 春山町 | `haruyamacho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-24 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 22 | 小松原 | `komatsubara` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 23 | 松原町 | `matsubaracho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-24 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 24 | 照国町 | `terukunicho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-24 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 25 | 上谷口町 | `kamitaniguchicho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 26 | 上福元町 | `kamifukumotocho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 27 | 上本町 | `kamihonmachi` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 28 | 上竜尾町 | `kamitatsuocho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 29 | 城山 | `shiroyama` | LOCAL_COMPLETE | Dedicated tool / 2026-07-24 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 30 | 城山町 | `shiroyamacho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-25 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 31 | 城西 | `josei` | LOCAL_COMPLETE | Dedicated tool / 2026-07-25 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 32 | 常盤 | `tokiwa` | LOCAL_COMPLETE | Dedicated tool / 2026-07-25 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 33 | 新栄町 | `shineicho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-25 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 34 | 新照院町 | `shinshoincho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-25 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 35 | 新町 | `shimmachi` | LOCAL_COMPLETE | Dedicated tool / 2026-07-28 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 36 | 真砂町 | `masagocho` | LOCAL_COMPLETE | Dedicated tool / 2026-07-28 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 37 | 真砂本町 | `masagohonmachi` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 38 | 星ヶ峯 | `hoshigamine` | LOCAL_COMPLETE | Dedicated tool / 2026-07-28 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 39 | 清水町 | `shimizucho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
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
| 50 | 千日町 | `sennichicho` | LOCAL_COMPLETE | Dedicated tool / 2026-09-29 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 51 | 川上町 | `kawakamicho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 52 | 川田町 | `kawadacho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 53 | 船津町 | `funatsucho` | LOCAL_COMPLETE | Dedicated tool / 2026-09-29 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 54 | 草牟田 | `soumuta` | LOCAL_COMPLETE | Dedicated tool / 2026-10-08 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 55 | 草牟田町 | `soumutacho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-08 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 56 | 大黒町 | `daikokucho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-08 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 57 | 大明丘 | `daimyogaoka` | LOCAL_COMPLETE | Dedicated tool / 2026-10-08 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 58 | 鷹師 | `takashi` | LOCAL_COMPLETE | Dedicated tool / 2026-10-08 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 59 | 谷山港 | `taniyamakou` | LOCAL_COMPLETE | Dedicated tool / 2026-10-08 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 60 | 谷山中央 | `taniyamachuuou` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 61 | 中央港新町 | `chuokoshinmachi` | LOCAL_COMPLETE | Status correction / 2026-10-09 / Three files and shared registration complete / PHP syntax verified / Current source Text is missing or unidentifiable / User confirmed on 2026-10-09 that the existing page must remain and this state is acceptable |
| 62 | 中央町 | `chuocho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 63 | 中山 | `chuzan` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 64 | 中山町 | `chuzancho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 65 | 中町 | `nakamachi` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 66 | 長田町 | `nagatacho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 67 | 直木町 | `naokicho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 68 | 田上 | `tagami` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 69 | 田上台 | `tagamidai` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 70 | 田上町 | `tagamicho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 71 | 唐湊 | `toso` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 72 | 東開町 | `tokaicho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 73 | 東郡元町 | `higashikoorimotocho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 74 | 東佐多町 | `higashisatacho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 75 | 東坂元 | `higashisakamoto` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 76 | 東千石町 | `higashisengokucho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 77 | 東谷山 | `higashitaniyama` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 78 | 東俣町 | `higashimatacho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 79 | 南栄 | `nanei` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 80 | 南郡元町 | `minamikorimotocho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 81 | 南新町 | `minamishinmachi` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 82 | 南林寺町 | `nanrinjicho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 83 | 日之出町 | `hinodecho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 84 | 樋之口町 | `tenokuchicho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 85 | 浜町 | `hamamachi` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 86 | 武 | `take` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 87 | 武岡 | `takeoka` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 88 | 福山町 | `fukuyamacho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 89 | 平川町 | `hirakawacho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 90 | 平田町 | `hiratacho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 91 | 平之町 | `hiranocho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 92 | 堀江町 | `horiecho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 93 | 本港新町 | `honkoshinmachi` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 94 | 本城町 | `honjocho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 95 | 本名町 | `honmyocho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 96 | 牟礼岡 | `muregaoka` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 97 | 名山町 | `meizancho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 98 | 明和 | `meiwa` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 99 | 柳町 | `yanagimachi` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 100 | 油須木町 | `yusukicho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 101 | 与次郎 | `yojiro` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 102 | 緑ヶ丘町 | `midorigaokacho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 103 | 冷水町 | `hiyamizucho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 104 | 和田 | `wada` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |
| 105 | 皷川町 | `tsuzugawacho` | LOCAL_COMPLETE | Dedicated tool / 2026-10-09 / Three files, shared registration, and static validation complete / PHP syntax verified |

Current image availability, artifact consistency, and eligibility are not
stored as queue-wide counts here. Use each row's status as workflow state, then
revalidate the selected target against actual files and the generated
current-state documents before production.
