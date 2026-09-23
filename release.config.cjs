const base = require('@camunda8/sdk-infra/configs/release.config.base.cjs');

// PHP packages are consumed from Packagist, which resolves versions from git tags.
// There is no build artifact to upload: publishing is "push the tag", after which
// scripts/notify-packagist.sh pings Packagist's update-package API so the new
// version syncs immediately (instead of waiting for the periodic crawl). We still
// stamp the resolved version into a PHP constant (src/Version.php) so the SDK can
// report its own version at runtime.
module.exports = {
  ...base,
  plugins: [
    ...base.plugins,
    [
      '@semantic-release/exec',
      {
        prepareCmd: 'bash scripts/prepare-release.sh "${nextRelease.version}"',
        successCmd: 'bash scripts/notify-packagist.sh "${nextRelease.version}"',
      },
    ],
    [
      '@semantic-release/git',
      {
        assets: ['CHANGELOG.md', 'src/Version.php'],
        message:
          'chore(release): ${nextRelease.version} [skip ci]\n\n${nextRelease.notes}',
      },
    ],
    [
      '@semantic-release/github',
      {
        successComment: false,
      },
    ],
  ],
};
