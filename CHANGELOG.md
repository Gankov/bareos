All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- add openssl 3 ulc [PR #1683]
- Add backport tool [PR #1639]

### Changed
- github actions: PyPi: install setuptools [PR #1589]
- restore: add fileregex parameter [PR #1587]
- scripts: force cd / for all PostgreSQL scripts [PR #1607]
- Improve python plugin configuration [PR #1605]
- macOS: fix linking problem for macOS >= 14 [PR #1592]
- dird: remove optimize_for_size/optimize_for_speed [PR #1612]
- build: introduce Fedora 39 [PR #1614]
- libcloud: modularize systemtest [PR #1609]
- filedaemon: remove ovirt plugin [PR #1617]
- vadp-dumper: fix multithreaded backup/restore issues [PR #1593]
- VMware Plugin: Run bareos_vadp_dumper with optimized parameters to increase backup performance [PR #1630]
- pkglists: update SUSE to have vmware packages [PR #1632]
- backup report: show negative compression [PR #1598]
- core: add build patch for `sprintf` in macos builds [PR #1636]
- python-bareos: use socket.create_connection() to allow AF_INET6 [PR #1646]
- Improve FreeBSD build [PR #1538]
- core: sql_* add leading space to sql construct [PR #1656]
- plugins: postgresql fix missing pg_backup_stop() call [PR #1655]
- filed: fix vss during client initiated connections [PR #1665]
- bareos-config: fix output of deploy_config [PR #1672]
- Disable automated package-tests for SLES 12 [PR #1671]
- Make BareosDirPluginPrometheusExporter.py work with python3 [PR #1647]
- Improve FreeBSD dependencies [PR #1670]
- python-bareos: integrate usage of config files [PR #1678]
- cmake: cleanup [PR #1661]
- bnet-server-tcp: split socket creation from listening for unittests [PR #1649]
- webui: Backup Unit Report fixes [PR #1696]
- windows: fix calculation of "job_metadata.xml" object size [PR #1695]
- stored: fix storage daemon crash if passive client is unreachable, create better session keys [PR #1688]
- bareos-triggerjob: fix parameter handling [PR #1708]
- fvec: add mmap based vector  [PR #1662]
- core: fix various data races (connection_pool/heartbeat_thread) [PR #1685]
- filed: skip stripped top level directories [PR #1686]
- jcr: fix some compiler warnings [PR #1648]
- build: Fix debugsource RPM package generation [PR #1713]
- Bugfix: Clean up error handling in LDAP plugin, fix dependencies [PR #1717]
- crypto_wrap: replace aes wrap with openssl aes wrap algorithm [PR #1718]

### Removed
- plugins: remove old deprecated postgres plugin [PR #1606]
- Remove EOL platforms [PR #1684]

### Documentation
- docs: improvements for droplet, jobdefs [PR #1581]

### Fixed
- dird: fix `purge oldest volume` [PR #1628]
- Fix continuation on colons in plugin baseclass [PR #1637]
- plugins: fix cancel handling crash [PR #1595]
- Fix bareos_tasks plugin for pgsql [PR #1659]
- core: Fix compile errors on GCC 14 [PR #1687]

[PR #1538]: https://github.com/bareos/bareos/pull/1538
[PR #1581]: https://github.com/bareos/bareos/pull/1581
[PR #1587]: https://github.com/bareos/bareos/pull/1587
[PR #1589]: https://github.com/bareos/bareos/pull/1589
[PR #1592]: https://github.com/bareos/bareos/pull/1592
[PR #1593]: https://github.com/bareos/bareos/pull/1593
[PR #1595]: https://github.com/bareos/bareos/pull/1595
[PR #1598]: https://github.com/bareos/bareos/pull/1598
[PR #1605]: https://github.com/bareos/bareos/pull/1605
[PR #1606]: https://github.com/bareos/bareos/pull/1606
[PR #1607]: https://github.com/bareos/bareos/pull/1607
[PR #1609]: https://github.com/bareos/bareos/pull/1609
[PR #1612]: https://github.com/bareos/bareos/pull/1612
[PR #1614]: https://github.com/bareos/bareos/pull/1614
[PR #1617]: https://github.com/bareos/bareos/pull/1617
[PR #1628]: https://github.com/bareos/bareos/pull/1628
[PR #1630]: https://github.com/bareos/bareos/pull/1630
[PR #1632]: https://github.com/bareos/bareos/pull/1632
[PR #1636]: https://github.com/bareos/bareos/pull/1636
[PR #1637]: https://github.com/bareos/bareos/pull/1637
[PR #1639]: https://github.com/bareos/bareos/pull/1639
[PR #1646]: https://github.com/bareos/bareos/pull/1646
[PR #1647]: https://github.com/bareos/bareos/pull/1647
[PR #1648]: https://github.com/bareos/bareos/pull/1648
[PR #1649]: https://github.com/bareos/bareos/pull/1649
[PR #1655]: https://github.com/bareos/bareos/pull/1655
[PR #1656]: https://github.com/bareos/bareos/pull/1656
[PR #1659]: https://github.com/bareos/bareos/pull/1659
[PR #1661]: https://github.com/bareos/bareos/pull/1661
[PR #1662]: https://github.com/bareos/bareos/pull/1662
[PR #1665]: https://github.com/bareos/bareos/pull/1665
[PR #1670]: https://github.com/bareos/bareos/pull/1670
[PR #1671]: https://github.com/bareos/bareos/pull/1671
[PR #1672]: https://github.com/bareos/bareos/pull/1672
[PR #1678]: https://github.com/bareos/bareos/pull/1678
[PR #1683]: https://github.com/bareos/bareos/pull/1683
[PR #1684]: https://github.com/bareos/bareos/pull/1684
[PR #1685]: https://github.com/bareos/bareos/pull/1685
[PR #1686]: https://github.com/bareos/bareos/pull/1686
[PR #1687]: https://github.com/bareos/bareos/pull/1687
[PR #1688]: https://github.com/bareos/bareos/pull/1688
[PR #1695]: https://github.com/bareos/bareos/pull/1695
[PR #1696]: https://github.com/bareos/bareos/pull/1696
[PR #1708]: https://github.com/bareos/bareos/pull/1708
[PR #1713]: https://github.com/bareos/bareos/pull/1713
[PR #1717]: https://github.com/bareos/bareos/pull/1717
[PR #1718]: https://github.com/bareos/bareos/pull/1718
[unreleased]: https://github.com/bareos/bareos/tree/master
