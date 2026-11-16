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
