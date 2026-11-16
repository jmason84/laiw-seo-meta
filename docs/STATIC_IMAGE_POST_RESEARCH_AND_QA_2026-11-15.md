# Launch AI Works — Static Image Posts: Research, Audit and QA Plan

Date: 2026-11-15 | Status: RESEARCH / DESIGN, NOT DEPLOYED | Scope: LAIW Facebook and X image posts, future Instagram images, WordPress article images, and link previews. Video research remains separate.

## Executive findings

1. Prioritize truthful visual content tied to sourced articles. Prefer real LAIW screenshots, documented processes and diagrams over repetitive generic AI/tech stock art; then licensed stock or clearly illustrative AI art.
2. Replace weak lexical photo matching with an image brief + ranked candidates + visual QA and reject/skip behavior. Current Pexels branch accepts a match if only ONE four-character word overlaps between query and photo alt text.
3. Produce channel-ready variants instead of repurposing one landscape image: propose feed portrait 1080 x 1350, square 1080 x 1080, and article link-preview 1200 x 630 as staging targets. Verify current destination-specific API limits before live use; never stretch the source.
4. Add verifiable rights, alt text, image dimensions, MIME/file-size checks, and immutable image review/hash tracking. Keep source photo ID, landing URL, license, photographer and all derivative IDs in a separate audit ledger rather than transient ErrorNotes.
5. Integrate the actual WordPress featured image / alternative text / Open Graph preview with the same reviewed master. The existing laiw-seo-meta.php already supplies og:image and twitter:image, falling back from per-page seo_image to featured image to 1200 x 630 default.
6. Maintain the existing hard publishing gate: Draft -> human approval -> article Published -> Copy Finisher replaces source link with LAIW article URL -> Auto-Distributor posts. Never copy third-party template automatic publication directly.

## Verified existing system — direct connector inspection on November 15

- Content Feeder https://automation.launchcollective.org/workflow/qROLaS3F14Glz2jN is active; Gemini image fallback uses gemini-2.5-flash-image; stock branch searches Pexels (landscape first) then Openverse. The generated image style is mostly generic cinematic technology.
- Stock — Pexels Select checks any overlapping substantial word in alt/title, then chooses first acceptable photo. Stock — Select allows CC0, PDM, and CC BY, with attribution; previous photo IDs are tracked.
- Stock — Patch Prep is a valuable VERIFIED ownership protection: re-read Google Sheet, then require unique QueueID AND matching SourceURL and title before exact-row update. Retain this protection.
- Stock provenance is currently stored in ErrorNotes, which can be overwritten later as publisher status/errors are reconciled. Stock — Download uses a dummy Openverse JSON API URL when no photo exists, which is a fragile no-image branch.
- Article Engine https://automation.launchcollective.org/workflow/7bUPmQYjDo6KPn7J uploads WordPress media and sets featured_media. Add a confirmed check of media alt_text, actual public media URL and article share image in a separate staged change.
- Distributor https://automation.launchcollective.org/workflow/H5RRejfcdYBpm1ii is active. Facebook image uses Page photos; X image uses X media upload; article-first gate blocks posting until LAIW article Published and final copy contains the article URL.
- WordPress repo https://github.com/jmason84/laiw-seo-meta contains an existing lightweight mu-plugin with og:image, twitter:image and summary_large_image. Do NOT alter plugin behavior just to add this research.
- Google Drive Post Queue: https://docs.google.com/spreadsheets/d/1RaU9E4kKjYWo93HrbIpRg6gIVrMJAeQlO43wnzkYHAo/edit . Media post image folder inherits a known anyone-with-link WRITER permission from a higher parent; fix separately under owner-approved cross-project permissions review, not in the social image pipeline change.

## Public n8n patterns to borrow — publisher descriptions, not imported or live-tested

| Source | Strong pattern | LAIW adaptation / caveat |
|---|---|---|
| https://n8n.io/workflows/15814-generate-seo-blog-drafts-from-google-sheets-to-wordpress-with-openai/ | Second AI image-selection pass, Pexels, photographer credit, alt text, WordPress featured image, logs | Best immediate source for image selection and article parity; keep draft approval |
| https://n8n.io/workflows/8064-generate-branded-article-images-with-gpt-4o-fal-flux-and-google-drive/ | Prompt -> image-to-image -> resize -> real-logo composite -> Drive | Borrow deterministic logo composition, not an invented logo |
| https://n8n.io/workflows/2459-overlay-or-watermark-images-by-merging-with-another-image/ | Composite overlays calculated using source dimensions | Check logo safety margins and output ratios |
| https://n8n.io/workflows/20734-create-and-approve-facebook-and-instagram-image-posts-via-telegram-and-gemini/ | Separate caption and rendered-image approval; bounded revisions | Do not introduce a second unapproved publisher |
| https://n8n.io/workflows/22322-publish-reviewed-google-drive-images-to-facebook-and-instagram-with-graph-api/ | Paid example of hash/version match, platform allowlist, dry run and original provider ID reconciliation | Valuable architecture; not verified in production by LAIW |
| https://n8n.io/workflows/13301-schedule-facebook-posts-from-google-sheets-with-approval-and-drive-images/ | Sheet-based review and scheduled photo posts | Its example stores Facebook credentials in a Sheet; NEVER copy that |
| https://n8n.io/workflows/15001-auto-post-facebook-and-instagram-content-with-openai-google-sheets-and-review/ | Reviewer checks exact caption and image URL, repetition counts | Preserve LAIW status and article-first policy |
| https://n8n.io/workflows/5159-ai-content-creation-and-auto-wordpress-publishing-with-pexels-api-image-workflow/ | Derive image query from a particular article; upload to WordPress | Disable auto-publish |
| https://n8n.io/workflows/8296-automated-blog-publishing-with-google-trends-gpt-4-pexels-and-wordpress/ | Watermark, image metadata, Pexels stock | Only publish verified relevant stories |
| https://n8n.io/workflows/11138-generate-ai-photos-with-gemini-and-auto-post-to-fb-instagram-and-x-with-approval/ | Regenerate visual before approval; per-channel tracking | Existing LAIW publisher remains sole execution path |

Important: these are public workflow/template descriptions. No external JSON has been safely imported, secrets reviewed, or live provider tests run here. Review full node graph and third-party packages before staging.

## Source and rights rules verified from official documentation

- Pexels API: 200 requests/hour and 20,000/month default; new API key issuance was reported paused as of Nov 15, 2026. Cache search results for about 24 hours; handle 429 with bounded backoff. Credits and API usage requirements are separate from the general Pexels photo license. Commercial photos cannot imply identifiable-person, logo or brand endorsement. https://www.pexels.com/api/documentation/ ; https://help.pexels.com/hc/en-us/articles/900006470063-What-steps-can-I-take-to-avoid-hitting-the-rate-limit ; https://help.pexels.com/hc/en-us/articles/900004904026-How-do-I-get-an-API-key ; https://help.pexels.com/hc/en-us/articles/360042332714-What-are-the-rules-for-using-Pexels-photos-or-videos
- Unsplash API guidelines require hotlinking returned photo.urls and calling download_location on an equivalent user-selected download. Attribution to photographer/Unsplash is expected for API applications. Review implementation compliance before adding as a third provider. https://help.unsplash.com/en/articles/2511245-unsplash-api-guidelines ; https://help.unsplash.com/en/articles/2511315-guideline-attribution
- Openverse is an aggregator, not a blanket copyright release. Open each originating source, check the exact work and license, and preserve CC BY credit in all required surfaces, not only Facebook caption. https://openverse.org/about
- Google Search encourages representative page images, descriptive filenames and alt text and discourages a generic logo or text image as the article image preview. https://developers.google.com/search/docs/appearance/google-images
- W3C distinguishes informative, decorative and complex images; write descriptive alt text for informative images and empty alt for truly decorative images. https://www.w3.org/WAI/tutorials/images/
- Facebook Graph photos supports an alt_text_custom parameter (verify exact posting endpoint/version). https://developers.facebook.com/docs/graph-api/reference/photo/
- n8n Edit Image supports crop, resize, composite and get-image-info; renderer requires testing against installed n8n/container dependencies. https://github.com/n8n-io/n8n-docs/blob/main/docs/integrations/builtin/core-nodes/n8n-nodes-base.editimage.md

## Editorial style and post/image matching

Choose among 6 formats rather than applying the same cinematic prompt: (A) verified LAIW screenshot with simple callouts, (B) useful process mini-diagram, (C) original factual checklist/card, (D) documented real team/process photo, (E) specific licensed stock scene, (F) clearly illustrative AI concept. Use the same brand identity across types but vary subject, composition and palette. Christian Sunday posts use suitable Bible/ministry themes rather than generic neon servers; never invent Bible verses, a real ministry deployment or a fake historical photo.

Suggested per-post image-plan fields: QueueID, ContentType, evidence/source URL, image type (screenshot/photo/stock/AI/diagram), intended subject, visual claim, specific search phrases, focal point, crop-critical objects, brand treatment, source URL, provider ID, creator, exact license and license URL, retrieved date, derivative IDs, alt text, expected channel variants, file hash, visual-review result, approval identifier. **This is a suggested separate ledger schema, not a production change**.

Editorial prompt to trial:
“Using only the verified source and article text, select a truthful visual angle. Say whether it is an original screenshot, licensed stock, or illustrative AI art. Return two precise search strings, concrete visible subjects, focal point, safe crop region, evidence/endorsement risks and natural alt text. Do not fabricate interfaces, events, people, product claims, client outcomes, statistics, logos or scripture. Reject the concept if it would imply misleading factual photography.”

## QA gates and phased build

P0 BASELINE — audit 30 existing image posts: stock relevance (blind human score), duplicate photo IDs/perceptual hashes, art variety, source/license ledger completeness, file validity, photo preview/OG image, alt-text coverage, and channel errors. No edits to historical posts.

P1 RELEVANCE — add image-plan and candidate selection branch in isolated staging; collect 6-16 candidates and rank for actual subjects, truthfulness, text/logo risk, contrast, crop flexibility, pixel dimensions and duplicate reuse. If no valid candidate, mark image needed and keep Draft rather than silently accepting an unrelated visual.

P2 FORMAT + BRAND — generate original master; derive 4:5, square and 1200x630 link variants. Preserve aspect; crop via focal point, no extreme upscaling, no tiny text. Composite real transparent LAIW logo after rendering where suitable; never regenerate logo in a model. Verify binary signature, dimensions, bytes and transparent/solid background.

P3 LICENSING + ACCESS — persistent ledger for exact license, creator, URL and attribution; private source master with tightly controlled reader delivery (or dedicated CDN) and no inherited public writer grants. Hash reviewed bytes, invalidate review on any mutation. Run URL and HTTP Content-Type check using same fetch path as the publisher.

P4 ARTICLE + SOCIAL — upload WordPress original/preview asset and alt text, verify featured_media, page OG image and actual 1200x630 preview, then replace social caption source link with the published LAIW article URL using existing Copy Finisher. Preserve article-first approval guard.

P5 PILOT — 10-20 staged Draft candidates; fixtures for 403/404/429, non-image payload, corrupt bytes, no results, wrong subject, duplicate, attribution, long copy, future schedule, changed approval, wrong QueueID, provider ambiguity and bad public image host. No live posting without owner-reviewed test set and rollback. Measure visual relevance, real retention/engagement, shares and article CTR; no unverified “viral” uplift claims.

ACCEPTANCE: 100% of approved image assets have an attributable rights/source trail; exact approved bytes used; visual relevance reviewed; platform-size/crop tested; WP alt/featured/OG verified; no auto-post on draft, stale article, changed image or ambiguous platform response; existing unique QueueID+SourceURL+title patch guard remains intact.

## Operational scope and handoff

Existing master Google Drive research: https://docs.google.com/document/d/1Y2JJ5h5pZORQCAKNCmS3KwZOJvRt6nGTgJhO2yvqoTY/edit
LAIW master Content Feeder plan: https://docs.google.com/document/d/1sxwjJeY78hmAVHlJQC7JOs4FrvZsQs2qxiTULu9tQws/edit
Canonical LAIW handoff: https://docs.google.com/document/d/1zloMmJVSwOLMdAfWe27i9q62gPBS6efBVH9S5Q_OqbM/edit

Evidence boundary: live connector code and the SEO plugin were inspected read-only, n8n examples researched from public publisher descriptions. BrowserOS neo connector was attempted but returned an internal tool error; Agent Browser CLI was not installed in the container. Public web search and connectors supplied the actual research. This commit documents an implementation plan only; no n8n, WordPress, Meta, X or Drive sharing mutation is included.


## Phase 2 research expansion — article-to-carousel workflows (2026-11-15)

**Scope / evidence:** Further research of current official n8n.io workflow listings, Meta first-party originality guidance, a 2026 Socialinsider engagement report, and direct **GitHub export/README** inspection. LAIW live distributor structure was independently inspected. These are researched patterns and market data; NO new carousel/approval/publisher workflow has been deployed, imported, executed with provider tokens, or enabled in production.

### Main decision: article-to-carousel is the next differentiating static-image format

A single 4:5 stock image per article has limited ability to teach. A **3–5 slide, fact-linked visual explainer** built from a verified published LAIW article can provide a distinctive reason to save and share. One slide should cover one independently verifiable takeaway. Example: a source-grounded hook, an annotated workflow diagram or real screenshot, a practical use case, a limitation/caution, and a closing slide with article URL. Do not produce extra daily posts merely to fill slots; select genuinely instructional articles. Make this an *optional creative type* within the existing Queue rather than an unreviewed second publishing calendar.

Performance evidence is **directional, not a promise**: the 2026 Socialinsider study reports stronger save behavior for Instagram carousels than single-image posts in its analyzed sample; Meta's own March 2026 originality guidance warns that minor edits and mere reposting lack substantive originality. For LAIW, prefer original synthesis, commentary, diagrams and real product/process evidence rather than repackaged stock images, and measure our own saves, clicks and on-site conversions.
- Meta March 2026: https://about.fb.com/news/2026/03/rewarding-original-creators-on-facebook/
- Meta January 2026 on originality emphasis: https://about.fb.com/news/2026/01/2026-ai-drives-performance/
- Socialinsider 2026 benchmarks: https://www.socialinsider.io/social-media-benchmarks/instagram-engagement-report

### New concrete n8n template review: most useful source-backed ideas

| Priority | Official template / verified URL | What the described workflow actually demonstrates | How we would adapt it safely |
|---|---|---|---|
| A | https://n8n.io/workflows/22250-turn-articles-into-instagram-and-linkedin-carousels-with-genmagic-google-drive-and-google-sheets/ | Form takes published article URL, extracts text, drafts carousel outline, creates one visual style frame, uses the frame to keep all slide images visually coherent, saves Google Drive assets and logs to Sheets. Uses a verified GenMagic community node; the author's claimed 7-slide price is approximately $0.45 at listed rates, subject to provider changes. | Replace form with queue row already linked to published LAIW article, constrain every slide to article evidence, render 3–5 slides, save to private Drive. Review node/package and provider pricing before installation. |
| A | https://n8n.io/workflows/22321-create-reviewed-instagram-carousel-pngs-with-openai-and-bannerbear-v5/ | **Two independent approvals**: factual text plan bound to source IDs, then assembled actual 1080×1350 PNGs. Exact text/template hashes, CRC/dimension checks, verified private Drive readback, ordered ZIP and no autopost. Listing shows a $5 paid template; external Bannerbear render costs extra. | Design target for robust approvals/ledger. Not required to purchase; recreate core principles using existing n8n/renderer. Note template owner says local mocks do not prove live typography/provider calls. |
| A | https://n8n.io/workflows/22214-create-instagram-and-facebook-carousels-from-articles-with-claude-openai-and-slack/ | Published article → three slide texts + hero image → branded Cloudinary transforms → Slack image preview/approval, option to rewrite cover or regenerate single image; Data Table tracks IDs. | Borrow versioned visual review and selective single-slide revision; keep publisher disconnected until approved LAIW distributor support is staged. |
| A | https://n8n.io/workflows/22317-create-and-review-ai-social-media-images-with-telegram-openai-and-google-drive/ | Price estimate approval, at most two originals plus one revision, actual PNG size/CRC checks, private readback, image/caption hashes, strict reviewer allowlist and no social publishing. Paid $5 listing, provider charges extra. | Strong single-image reviewer design, bounded spending and paid-call uncertainty handling; use current Google Workspace review preference rather than requiring Telegram. |
| B | https://n8n.io/workflows/18746-publish-instagram-carousels-from-google-sheets-using-gemini-and-gcs/ | Google Sheets → Gemini 5–6 slides → native Edit Image resize 1080×1080 → GCS URLs → IG child-container status polling → parent container → post IDs. Free template listing. | Borrow child/parent media-container status polling and ordered child IDs only after Instagram eligibility, privileges and publishing design are verified; remove its public-upload/publish path in the drafting phase. |
| B | https://n8n.io/workflows/19934-post-daily-instagram-news-carousels-using-claude-and-gemini/ | RSS topic rotation → Claude caption and slide plan → Gemini images and logo → Amazon S3 → IG; uses helper-workflow renderer. | One source-grounded article and helper-workflow isolation instead of blind RSS headline mix or automatic publication. |
| B | https://n8n.io/workflows/5833-automate-instagram-carousel-posts-with-google-sheets-drive-and-cloudinary/ | Drive multi-image delivery via Cloudinary and Meta IG carousel creation. | Only relevant when we expand the verified distributor; use existing media-hosting requirements and explicit credential isolation. |
| B | https://n8n.io/workflows/7601-instagram-carousel-posts-from-google-drive-via-cloudinary-with-telegram-alerts/ | Per-image Drive downloads, Cloudinary hosting, multiple media IDs and Telegram notifications. | Asset processing / ID aggregation concepts; no second publishing path. |
| B | https://n8n.io/workflows/8398-create-ai-powered-social-media-content-for-linkedin-and-facebook-with-image-generation-and-human-approval/ | Multi-image brand brief, channel-specific ratios, Slack approval, one-image feedback/edits, Drive and Sheets record. Several extra paid services. | Keep the per-post reviewer/editor feedback loop, not the paid dependency stack or Late publisher. |
| B | https://n8n.io/workflows/20967-validate-social-media-posts-in-google-sheets-and-send-issues-to-slack/ | Dedicated row validation checks post IDs, platform, copy, URL and duplicates and logs issue status. | Add preflight audit as a **read-only** / non-posting stage first; don't let it change live Approved statuses until tested. |
| B | https://n8n.io/workflows/16018-route-social-media-content-reviews-with-google-gemini-and-google-sheets/ | Strategy angle selection, writing revision, quality audit, human approval at all three stages. | Useful for a three-option creative brief review; can simplify to one human image-plan approval + final actual image approval. |
| C | https://n8n.io/workflows/4028-generate-and-publish-carousels-for-tiktok-and-instagram-with-gpt-image-1/ | Previous generated image guides the next (style continuity); automatic publish through third-party service. | Only consider image-reference technique in a sandbox; do not port autopost or assume accurate diagrams/text in generated artwork. |
| C | https://n8n.io/workflows/3693-create-instagram-carousel-posts-with-gpt-41-mini-imgur-and-graph-api/ | Claims pgvector semantic similarity over past ideas for novelty, and schema validation. | Idea-level duplication check is interesting; index finished LAIW article topics and images first. Full claimed implementation not independently audited. |
| C | https://n8n.io/workflows/7307-generate-and-quality-control-images-with-dall-e-2-and-human-review-loop/ | Simple bounded regenerate-after-rejection loop using GotoHuman. | Concept only; listed legacy image model may not be suitable or available. |

**Selection rule:** Start with free, native, and *non-publishing* components (#22250 structural pattern, #22321 approval design, #20967 validation) before adding paid community nodes, alternate upload/publisher services or external public image hosts. Confirm actual version/credential rights/feature set in staging; template landing-page copy is not test evidence.

### Direct GitHub exported-workflow audits (stronger evidence than README marketing)

1. https://github.com/Jackson0Wells/instagram-carousel-uploader-n8n/blob/main/My_workflow.json — fetched from GitHub and JSON parsed; **23 nodes**: Schedule Trigger, Google Sheets read/write, Gemini LLM caption, multiple If branches and Facebook Graph nodes for 1–4 images, Merge and carousel container. README describes single-row selection and uploaded flag. Useful per-image branching and child-ID collection, but it is a direct publisher and there is no evidence of LAIW safety gates or current endpoint compatibility. No credentials were copied or published.
2. https://github.com/Rana3112/AI-instagram-automation-n8n/blob/main/workflows/tech_news_multi_carousel_v25.json — fetched and parsed; **41 nodes**, including 13 RSS inputs, 7 HTTP requests, 12 Code, 2 Merge, 2 Wait and 1 If. Its orchestration delegates carousel image rendering to a private HTTP service, and the actual export uses **Graph API v18.0**, a hardcoded instance/account ID and a private network renderer hostname. This is NOT a drop-in LAIW import. Reuse the separation of orchestration and image renderer and the media-status workflow concepts only.
3. https://github.com/Rana3112/AI-instagram-automation-n8n/blob/main/workflows/ai_news_multi_carousel_v1.json — also 41 nodes, but targeted AI-news feed queries. Treat as a close variant of the previous export, not a wholly distinct tested solution. Avoid depending on third-party fixed entity/topic lists without citation verification.
4. https://github.com/ralphcrisostomo/n8n-nodes-meta-publisher/blob/main/README.md — a community node README describes a unified Meta/Threads publisher with optional carousel 2–10 items and polling. **Auto-publish defaults true in examples** and it adds a community package. If ever evaluated, sandbox-review its actual code, plugin integrity, token handling, retries, no-publish dry-run behavior and versions. Existing native LAIW publisher is preferred.

**Live LAIW constraint independently checked:** Social Auto-Distributor v3 (42 nodes) has single Facebook photo / X media upload paths but **no carousel-specific node**. It cannot honestly be described as supporting carousel publication today. The Article Engine and Copy Finisher can still produce the article and final article links. A separate *Draft-only Carousel Factory* should produce an ordered manifest and assets without changing the currently active posting graph. Keep Facebook/X single-image posting unchanged while eligibility and an eventual carousel publisher are assessed.

### Proposed minimal architecture and data contract

**Workflow 1: Article → Carousel Draft (read-only origin; writes only review assets)**

1. Read one Post Queue row with an actual public Published article URL. A newly drafted article is not eligible yet.
2. Fetch the article and source dossier. Create an **exact fact-ID ledger**, including sentence/span and original citation URL; do not invent claims when article sources are thin.
3. Editorial classifier: skip carousel when there are fewer than 3 substantial independently supported takeaways; instead retain current single-image content.
4. Generate 3–5 slides: problem/hook, explanation, demo/diagram, a limitation/caveat, and article CTA. For each, store fact IDs, plain-text headline, one evidence-backed assertion, visual type, alt text and crop. For faith/Christian tool posts, use the same verified evidence rules and avoid fabricated Bible quotations or invented pastoral adoption.
5. Validate: schema, title line budget, exact source facts, chronology, numbers, link target, license, prohibited logos/screenshots or unauthorized people; reject any missing evidence. Human review approves **text plan** and its immutable hash.
6. Render using one deterministic style frame and text layout (SVG/HTML-to-image/Sharp, n8n Edit Image with GraphicsMagick when available, or scoped external renderer). AI generates backgrounds only; insert **text and the real logo with deterministic text engine** to avoid hallucinated letters.
7. Check actual PNG/JPEG structure, dimensions, legibility/contrast/crop, slide count/order, no material edits since approval and matching image hashes. Show a preview sheet / private ZIP to reviewer.
8. Human review approves the complete ordered image set. Persist a unique CarouselAssetSetID, article ID, fact ledger, source/rights metadata, original and derivative image IDs, image hashes, approval identity/timestamp and the final published-article link. Keep current Queue media fields unchanged; optional new mapping lives in a sidecar table until tested.

**Workflow 2: Carousel Publishing Adapter (FUTURE, not approved or deployed)**

Add only after confirmed Instagram account connection and Meta permissions, image URL reachability, platform crop specs and deduplicated container-ID handling. Use live article-first guard; route an approved, unchanged manifest to Instagram carousel child containers; bounded poll FINISHED for each child and parent; publish once, reconcile unknown outcome before retry, write returned platform post IDs. Keep existing FB/X single-image publishing path until a separate tested multi-photo / multi-image route is approved. Never allow a new vendor service to silently publish directly.

**Minimal proposed sidecar schema** (not yet created):
- asset_set_id, queue_id, wordpress_post_id, article_url, article_status, creative_type
- slide_order, fact_ids, text_plan_sha256, source_url, original_photo_license, author/attribution_url
- image_sha256, binary_verified, dimensions, renderer_version, alt_text, variant_url, public_delivery_expiry
- review_text_status / reviewed_by / reviewed_at, review_image_status / reviewed_by / reviewed_at, approved_manifest_sha256
- platform, publish_attempt_id, provider_media_container_id, provider_post_id, publish_status, reconciled_at, failure_reason

Do not store original media rights in Queue ErrorNotes alone: that field is also the error/status log and can be overwritten. Store immutable media provenance in the sidecar and reference it from a stable ID.

### Research-informed experiment plan, not a guarantee of higher reach

Run *controlled* creative experiments after staging and approval. Compare: (A) existing representative licensed photo, (B) a real product screenshot with annotations, (C) a 4-slide original explainer carousel. Do not compare formats on entirely unrelated topics or different audiences and then claim causal improvement. Track by format and article: 7-day impressions, saves, shares, comments, link clicks, qualified page visits and time on page, plus costs per drafted and approved post. Use per-post denominators and record sample sizes; no claim of increased engagement until actual LAIW measurements exist.

**Recommended implementation sequence:** (1) design one 4-slide reference mock from a real LAIW article in sandbox, (2) validate schema and source IDs without paid generation, (3) render actual 1080×1350 assets with deterministic typography and exact byte QA, (4) owner approves a preview/manifest, (5) store privately and append a non-publishing review record, (6) only then plan an Instagram/FB adapter. Avoid introducing any production publishing changes during research or prototypes.

**External source verification note:** n8n.io listings describe intended behavior, not audited deployment. The two GitHub JSON exports and the repo README were actually fetched/read. Live image/social workflows were inspected read-only. No new template was imported, rendered, published, or paid for.
